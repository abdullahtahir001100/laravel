<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Follow;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $authUser = $request->user();
        $friends = $this->friendsFor($authUser);
        $activeFriendId = (int) $request->integer('friend_id');
        $activeFriend = $friends->firstWhere('id', $activeFriendId) ?? $friends->first();
        $messages = collect();

        if ($activeFriend) {
            $this->markThreadRead($authUser->id, $activeFriend['id']);
            $messages = Message::query()
                ->betweenUsers($authUser->id, $activeFriend['id'])
                ->with(['sender:id,first_name,last_name,display_name,avatar_path', 'recipient:id,first_name,last_name,display_name,avatar_path'])
                ->orderBy('created_at')
                ->get();
        }

        return view('messages', [
            'friends' => $friends,
            'activeFriend' => $activeFriend,
            'messages' => $messages,
            'chatChannel' => $activeFriend
                ? $this->conversationChannel($authUser->id, $activeFriend['id'])
                : null,
            'authUserId' => $authUser->id,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $authUser = $request->user();
        $recipient = User::query()->findOrFail($validated['recipient_id']);

        if (! $this->isAcceptedFriend($authUser->id, $recipient->id)) {
            return response()->json([
                'message' => 'You can only message accepted friends.',
            ], 403);
        }

        $message = Message::query()->create([
            'sender_id' => $authUser->id,
            'recipient_id' => $recipient->id,
            'body' => trim($validated['message']),
        ]);

        $message->load([
            'sender:id,first_name,last_name,display_name,avatar_path',
            'recipient:id,first_name,last_name,display_name,avatar_path',
        ]);

        $payload = $this->formatMessage($message);

        Log::info('MESSAGE STORED', $payload);
        event(new MessageSent($payload));

        return response()->json([
            'status' => 'sent',
            'message' => $payload,
        ]);
    }

    public function thread(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'friend_id' => ['required', 'integer', 'exists:users,id'],
            'after_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $authUser = $request->user();
        $friendId = (int) $validated['friend_id'];
        $afterId = (int) ($validated['after_id'] ?? 0);

        if (! $this->isAcceptedFriend($authUser->id, $friendId)) {
            return response()->json([
                'message' => 'You can only load threads for accepted friends.',
            ], 403);
        }

        $query = Message::query()
            ->betweenUsers($authUser->id, $friendId)
            ->where('id', '>', $afterId)
            ->with([
                'sender:id,first_name,last_name,display_name,avatar_path',
                'recipient:id,first_name,last_name,display_name,avatar_path',
            ])
            ->orderBy('id');

        $messages = $query->get();

        Message::query()
            ->where('sender_id', $friendId)
            ->where('recipient_id', $authUser->id)
            ->whereNull('read_at')
            ->where('id', '>', $afterId)
            ->update([
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        $formatted = $messages->map(fn (Message $message) => $this->formatMessage($message))->values();

        return response()->json([
            'messages' => $formatted,
            'last_id' => (int) ($formatted->last()['id'] ?? $afterId),
        ]);
    }

    public function markRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sender_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $authUser = $request->user();
        $senderId = (int) $validated['sender_id'];

        $updated = Message::query()
            ->where('sender_id', $senderId)
            ->where('recipient_id', $authUser->id)
            ->whereNull('read_at')
            ->update([
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        return response()->json([
            'status' => 'ok',
            'updated' => $updated,
        ]);
    }

    private function friendsFor(User $authUser): Collection
    {
        $friendIds = Follow::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($authUser) {
                $query->where('follower_id', $authUser->id)
                    ->orWhere('following_id', $authUser->id);
            })
            ->get()
            ->flatMap(function (Follow $follow) use ($authUser) {
                return [
                    $follow->follower_id === $authUser->id ? $follow->following_id : $follow->follower_id,
                ];
            })
            ->unique()
            ->values();

        $users = User::query()
            ->whereIn('id', $friendIds)
            ->get(['id', 'first_name', 'last_name', 'display_name', 'email', 'avatar_path']);

        return $users->map(function (User $friend) use ($authUser) {
            $conversation = Message::query()
                ->betweenUsers($authUser->id, $friend->id)
                ->latest('created_at')
                ->first();

            $unreadCount = Message::query()
                ->where('sender_id', $friend->id)
                ->where('recipient_id', $authUser->id)
                ->whereNull('read_at')
                ->count();

            return [
                'id' => $friend->id,
                'displayName' => $friend->display_name
                    ?: trim(($friend->first_name ?? '') . ' ' . ($friend->last_name ?? ''))
                    ?: 'User',
                'avatarUrl' => $friend->avatar_path ? asset('storage/' . $friend->avatar_path) : null,
                'profileUrl' => route('user.profile', $friend->id),
                'lastMessage' => $conversation?->body,
                'lastMessageAt' => $conversation?->created_at?->diffForHumans(),
                'unreadCount' => $unreadCount,
            ];
        })->values();
    }

    private function isAcceptedFriend(int $firstUserId, int $secondUserId): bool
    {
        return Follow::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where(function ($inner) use ($firstUserId, $secondUserId) {
                    $inner->where('follower_id', $firstUserId)
                        ->where('following_id', $secondUserId);
                })->orWhere(function ($inner) use ($firstUserId, $secondUserId) {
                    $inner->where('follower_id', $secondUserId)
                        ->where('following_id', $firstUserId);
                });
            })
            ->exists();
    }

    private function markThreadRead(int $viewerId, int $friendId): void
    {
        Message::query()
            ->where('sender_id', $friendId)
            ->where('recipient_id', $viewerId)
            ->whereNull('read_at')
            ->update([
                'delivered_at' => now(),
                'read_at' => now(),
            ]);
    }

    private function conversationChannel(int $firstUserId, int $secondUserId): string
    {
        $low = min($firstUserId, $secondUserId);
        $high = max($firstUserId, $secondUserId);

        return 'chat.' . $low . '.' . $high;
    }

    private function formatMessage(Message $message): array
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'recipient_id' => $message->recipient_id,
            'body' => $message->body,
            'status' => $message->status,
            'created_at' => $message->created_at?->toIso8601String(),
            'time' => $message->created_at?->format('g:i A'),
            'sender_name' => $message->sender?->display_name
                ?: trim(($message->sender?->first_name ?? '') . ' ' . ($message->sender?->last_name ?? ''))
                ?: 'User',
            'recipient_name' => $message->recipient?->display_name
                ?: trim(($message->recipient?->first_name ?? '') . ' ' . ($message->recipient?->last_name ?? ''))
                ?: 'User',
        ];
    }
}
