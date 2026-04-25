<?php

namespace App\Http\Controllers;

use App\Models\ContentComment;
use App\Models\ContentInterest;
use App\Models\ContentItem;
use App\Models\ContentLike;
use App\Models\Follow;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    public function feed(Request $request): JsonResponse
    {
        $scope = $request->string('scope', 'main')->value();
        $tag = trim((string) $request->string('tag')->value());
        $user = $request->user();

        $query = ContentItem::query()
            ->with('user:id,first_name,last_name,display_name,avatar_path')
            ->with(['likes.user:id,first_name,last_name,display_name,avatar_path'])
            ->withCount(['likes', 'comments'])
            ->where('visibility', 'public')
            ->whereNotIn('id', function ($sub) use ($user) {
                $sub->select('content_item_id')
                    ->from('content_interests')
                    ->where('user_id', $user->id)
                    ->where('state', 'not_interested');
            });

        if ($scope === 'posts') {
            $query->where('content_type', 'post')->where('media_type', 'image');
        } elseif ($scope === 'reels') {
            $query->where('content_type', 'reel');
        } elseif ($scope === 'live') {
            $query->where('content_type', 'live');
        } else {
            $query->whereIn('content_type', ['post', 'reel', 'live']);
        }

        if ($tag !== '') {
            $query->whereJsonContains('tags', $tag);
        }

        $items = $query
            ->latest('published_at')
            ->latest('id')
            ->paginate(20)
            ->through(fn (ContentItem $item) => $this->transformItem($item, $user->id));

        return response()->json($items);
    }

    public function toggleLike(Request $request, ContentItem $contentItem): JsonResponse
    {
        $user = $request->user();

        $existing = ContentLike::query()
            ->where('content_item_id', $contentItem->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            ContentLike::create([
                'content_item_id' => $contentItem->id,
                'user_id' => $user->id,
            ]);
            $liked = true;

            if ($contentItem->user_id !== $user->id) {
                UserNotification::create([
                    'recipient_id' => $contentItem->user_id,
                    'actor_id' => $user->id,
                    'content_item_id' => $contentItem->id,
                    'type' => 'like',
                ]);
            }
        }

        return response()->json([
            'liked' => $liked,
            'likesCount' => $contentItem->likes()->count(),
        ]);
    }

    public function comments(ContentItem $contentItem): JsonResponse
    {
        $comments = ContentComment::query()
            ->with('user:id,first_name,last_name,display_name,avatar_path')
            ->where('content_item_id', $contentItem->id)
            ->latest('id')
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->map(function (ContentComment $comment) {
                $displayName = $comment->user?->display_name
                    ?: trim(($comment->user?->first_name ?? '') . ' ' . ($comment->user?->last_name ?? ''))
                    ?: 'User';

                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'createdAt' => optional($comment->created_at)->diffForHumans(),
                    'user' => [
                        'id' => $comment->user?->id,
                        'displayName' => $displayName,
                        'avatarUrl' => $comment->user?->avatar_path ? asset('storage/' . $comment->user->avatar_path) : null,
                        'profileUrl' => $comment->user?->id ? route('user.profile', $comment->user->id) : '#',
                    ],
                ];
            });

        return response()->json(['comments' => $comments]);
    }

    public function addComment(Request $request, ContentItem $contentItem): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $comment = ContentComment::create([
            'content_item_id' => $contentItem->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
        ]);

        if ($contentItem->user_id !== $user->id) {
            UserNotification::create([
                'recipient_id' => $contentItem->user_id,
                'actor_id' => $user->id,
                'content_item_id' => $contentItem->id,
                'type' => 'comment',
                'payload' => ['body' => $validated['body']],
            ]);
        }

        return response()->json([
            'message' => 'Comment added',
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'createdAt' => optional($comment->created_at)->diffForHumans(),
            ],
            'commentsCount' => $contentItem->comments()->count(),
        ]);
    }

    public function notInterested(Request $request, ContentItem $contentItem): JsonResponse
    {
        $user = $request->user();

        ContentInterest::updateOrCreate(
            [
                'content_item_id' => $contentItem->id,
                'user_id' => $user->id,
            ],
            [
                'state' => 'not_interested',
            ]
        );

        if ($contentItem->user_id !== $user->id) {
            UserNotification::create([
                'recipient_id' => $contentItem->user_id,
                'actor_id' => $user->id,
                'content_item_id' => $contentItem->id,
                'type' => 'not_interested',
            ]);
        }

        return response()->json(['message' => 'Content hidden from your feed']);
    }

    public function follow(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        $follow = Follow::query()
            ->where('follower_id', $authUser->id)
            ->where('following_id', $user->id)
            ->first();

        if ($follow) {
            $existingStatus = $follow->status;
            $followId = $follow->id;
            $follow->delete();

            return response()->json([
                'message' => 'Unfollowed',
                'status' => 'none',
                'buttonLabel' => 'Follow',
                'was' => $existingStatus,
                'followId' => null,
            ]);
        }

        $follow = Follow::query()->create([
            'follower_id' => $authUser->id,
            'following_id' => $user->id,
            'status' => 'requested',
            'accepted_at' => null,
        ]);

        UserNotification::create([
            'recipient_id' => $user->id,
            'actor_id' => $authUser->id,
            'type' => 'follow_request',
        ]);

        return response()->json([
            'message' => 'Follow request sent',
            'status' => $follow->status,
            'buttonLabel' => 'Requested',
            'followId' => $follow->id,
        ]);
    }

    public function suggestedUsers(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $users = User::query()
            ->where('id', '!=', $authUser->id)
            ->orderByDesc('id')
            ->limit(8)
            ->get(['id', 'first_name', 'last_name', 'display_name', 'username', 'headline', 'avatar_path']);

        return response()->json([
            'users' => $users->map(fn (User $user) => $this->userPayload($user, $authUser->id)),
        ]);
    }

    public function acceptFollow(Request $request, Follow $follow): JsonResponse
    {
        abort_unless($follow->following_id === $request->user()->id, 403);

        $follow->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        UserNotification::create([
            'recipient_id' => $follow->follower_id,
            'actor_id' => $request->user()->id,
            'type' => 'follow_accepted',
        ]);

        return response()->json(['message' => 'Follow request accepted']);
    }

    public function friends(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $friendIds = Follow::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($authUser) {
                $q->where('follower_id', $authUser->id)
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

        return response()->json([
            'friends' => $users->map(fn (User $friend) => [
                'id' => $friend->id,
                'displayName' => $friend->display_name
                    ?: trim(($friend->first_name ?? '') . ' ' . ($friend->last_name ?? ''))
                    ?: 'User',
                'email' => $friend->email,
                'avatarUrl' => $friend->avatar_path ? asset('storage/' . $friend->avatar_path) : null,
                'profileUrl' => route('user.profile', $friend->id),
            ]),
        ]);
    }

    public function userSearch(Request $request): JsonResponse
    {
        $q = trim((string) $request->string('q')->value());
        $authUser = $request->user();

        $users = User::query()
            ->where('id', '!=', $authUser->id)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('display_name', 'like', '%' . $q . '%')
                        ->orWhere('first_name', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%');
                });
            })
            ->limit(50)
            ->get(['id', 'first_name', 'last_name', 'display_name', 'email', 'avatar_path']);

        return response()->json([
            'users' => $users->map(fn (User $user) => $this->userPayload($user, $authUser->id)),
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $items = UserNotification::query()
            ->with('actor:id,first_name,last_name,display_name,avatar_path')
            ->where('recipient_id', $request->user()->id)
            ->latest('id')
            ->limit(100)
            ->get()
            ->map(function (UserNotification $item) {
                $actorName = $item->actor?->display_name
                    ?: trim(($item->actor?->first_name ?? '') . ' ' . ($item->actor?->last_name ?? ''))
                    ?: 'System';

                $payload = $item->payload ?? [];
                
                // Include follow ID for follow-related notifications
                if ($item->type === 'follow_request' || $item->type === 'follow_accepted') {
                    $follow = Follow::query()
                        ->where(function ($q) use ($item) {
                            $q->where('follower_id', $item->actor_id)
                                ->where('following_id', $item->recipient_id);
                        })
                        ->orWhere(function ($q) use ($item) {
                            $q->where('follower_id', $item->recipient_id)
                                ->where('following_id', $item->actor_id);
                        })
                        ->first();
                    if ($follow) {
                        $payload['follow_id'] = $follow->id;
                    }
                }

                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'actorName' => $actorName,
                    'actorId' => $item->actor_id,
                    'actorAvatarUrl' => $item->actor?->avatar_path ? asset('storage/' . $item->actor->avatar_path) : null,
                    'payload' => $payload,
                    'isRead' => (bool) $item->read_at,
                    'time' => optional($item->created_at)->diffForHumans(),
                ];
            });

        return response()->json(['notifications' => $items]);
    }

    public function cancelFollow(Request $request, Follow $follow): JsonResponse
    {
        // Check if the user is the follower (they're canceling their own request/follow)
        abort_unless($follow->follower_id === $request->user()->id, 403);

        $follow->delete();

        return response()->json(['message' => 'Follow request cancelled']);
    }

    public function rejectFollow(Request $request, Follow $follow): JsonResponse
    {
        // Check if the user is the one being followed (they're rejecting the request)
        abort_unless($follow->following_id === $request->user()->id, 403);

        $follow->delete();

        return response()->json(['message' => 'Follow request rejected']);
    }

    public function followers(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $followers = Follow::query()
            ->with('follower:id,first_name,last_name,display_name,email,avatar_path')
            ->where('following_id', $authUser->id)
            ->latest('id')
            ->get(['id', 'follower_id', 'following_id', 'status', 'accepted_at', 'created_at'])
            ->map(function (Follow $follow) {
                $follower = $follow->follower;
                $displayName = $follower?->display_name
                    ?: trim(($follower?->first_name ?? '') . ' ' . ($follower?->last_name ?? ''))
                    ?: 'User';

                return [
                    'id' => $follow->id,
                    'followerId' => $follower?->id,
                    'displayName' => $displayName,
                    'email' => $follower?->email,
                    'avatarUrl' => $follower?->avatar_path ? asset('storage/' . $follower->avatar_path) : null,
                    'profileUrl' => $follower?->id ? route('user.profile', $follower->id) : '#',
                    'status' => $follow->status,
                    'acceptedAt' => $follow->accepted_at,
                    'requestedAt' => $follow->created_at,
                ];
            });

        return response()->json(['followers' => $followers]);
    }

    public function following(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $following = Follow::query()
            ->with('following:id,first_name,last_name,display_name,email,avatar_path')
            ->where('follower_id', $authUser->id)
            ->latest('id')
            ->get(['id', 'follower_id', 'following_id', 'status', 'accepted_at', 'created_at'])
            ->map(function (Follow $follow) {
                $user = $follow->following;
                $displayName = $user?->display_name
                    ?: trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''))
                    ?: 'User';

                return [
                    'id' => $follow->id,
                    'userId' => $user?->id,
                    'displayName' => $displayName,
                    'email' => $user?->email,
                    'avatarUrl' => $user?->avatar_path ? asset('storage/' . $user->avatar_path) : null,
                    'profileUrl' => $user?->id ? route('user.profile', $user->id) : '#',
                    'status' => $follow->status,
                    'acceptedAt' => $follow->accepted_at,
                    'requestedAt' => $follow->created_at,
                ];
            });

        return response()->json(['following' => $following]);
    }

    public function followStatusForUser(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->id === $user->id) {
            return response()->json([
                'status' => 'self',
                'buttonLabel' => 'You',
                'followId' => null,
                'isFriend' => false,
            ]);
        }

        $status = $this->followStatus($authUser->id, $user->id);
        $followId = $this->getFollowId($authUser->id, $user->id);

        $isFriend = Follow::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($authUser, $user) {
                $q->where(function ($inner) use ($authUser, $user) {
                    $inner->where('follower_id', $authUser->id)
                        ->where('following_id', $user->id);
                })->orWhere(function ($inner) use ($authUser, $user) {
                    $inner->where('follower_id', $user->id)
                        ->where('following_id', $authUser->id);
                });
            })
            ->exists();

        return response()->json([
            'status' => $status,
            'buttonLabel' => $this->followLabel($status),
            'followId' => $followId,
            'isFriend' => $isFriend,
        ]);
    }

    private function transformItem(ContentItem $item, int $authUserId): array
    {
        $displayName = $item->user?->display_name
            ?: trim(($item->user?->first_name ?? '') . ' ' . ($item->user?->last_name ?? ''))
            ?: 'User';

        $likedByMe = ContentLike::query()
            ->where('content_item_id', $item->id)
            ->where('user_id', $authUserId)
            ->exists();

        $followStatus = $this->followStatus($authUserId, $item->user_id);
        $followId = $this->getFollowId($authUserId, $item->user_id);
        
        // Check if friend (both directions)
        $isFriend = Follow::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($authUserId, $item) {
                $q->where('follower_id', $authUserId)->where('following_id', $item->user_id)
                    ->orWhere('follower_id', $item->user_id)->where('following_id', $authUserId);
            })
            ->exists();

        $recentLikes = $item->likes
            ?->take(6)
            ->values()
            ->map(function (ContentLike $like) {
                $likeUser = $like->user;

                if (! $likeUser) {
                    return null;
                }

                $likeName = $likeUser->display_name
                    ?: trim(($likeUser->first_name ?? '') . ' ' . ($likeUser->last_name ?? ''))
                    ?: 'User';

                return [
                    'id' => $likeUser->id,
                    'displayName' => $likeName,
                    'avatarUrl' => $likeUser->avatar_path ? asset('storage/' . $likeUser->avatar_path) : null,
                    'profileUrl' => route('user.profile', $likeUser->id),
                ];
            })
            ->filter()
            ->values();

        return [
            'id' => $item->id,
            'userId' => $item->user_id,
            'authorName' => $displayName,
            'authorAvatarUrl' => $item->user?->avatar_path ? asset('storage/' . $item->user->avatar_path) : null,
            'authorProfileUrl' => route('user.profile', $item->user_id),
            'authorFollowStatus' => $followStatus,
            'authorFollowId' => $followId,
            'authorFollowLabel' => $this->followLabel($followStatus),
            'isFriend' => $isFriend,
            'type' => $item->content_type,
            'title' => $item->title,
            'subtitle' => $item->subtitle,
            'description' => $item->description,
            'tags' => $item->tags ?? [],
            'visibility' => $item->visibility,
            'mediaUrl' => $item->media_path ? Storage::url($item->media_path) : null,
            'mediaType' => $item->media_type,
            'likesCount' => $item->likes_count,
            'commentsCount' => $item->comments_count,
            'likedByMe' => $likedByMe,
            'recentLikes' => $recentLikes,
            'publishedAt' => optional($item->published_at ?? $item->created_at)->diffForHumans(),
        ];
    }

    private function followStatus(int $authUserId, int $targetUserId): string
    {
        $follow = Follow::query()
            ->where('follower_id', $authUserId)
            ->where('following_id', $targetUserId)
            ->first();

        if (! $follow) {
            return 'none';
        }

        return $follow->status === 'accepted' ? 'accepted' : 'requested';
    }

    private function getFollowId(int $authUserId, int $targetUserId): ?int
    {
        return Follow::query()
            ->where('follower_id', $authUserId)
            ->where('following_id', $targetUserId)
            ->value('id');
    }

    private function followLabel(string $status): string
    {
        return match ($status) {
            'requested' => 'Requested',
            'accepted' => 'Following',
            default => 'Follow',
        };
    }

    private function userPayload(User $user, int $authUserId): array
    {
        $displayName = $user->display_name
            ?: trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
            ?: 'User';

        $status = $this->followStatus($authUserId, $user->id);
        $followId = $this->getFollowId($authUserId, $user->id);
        
        // Check if friend
        $isFriend = Follow::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($authUserId, $user) {
                $q->where('follower_id', $authUserId)->where('following_id', $user->id)
                    ->orWhere('follower_id', $user->id)->where('following_id', $authUserId);
            })
            ->exists();

        return [
            'id' => $user->id,
            'displayName' => $displayName,
            'username' => $user->username,
            'headline' => $user->headline,
            'avatarUrl' => $user->avatar_path ? asset('storage/' . $user->avatar_path) : null,
            'profileUrl' => route('user.profile', $user->id),
            'followStatus' => $status,
            'followId' => $followId,
            'followLabel' => $this->followLabel($status),
            'isFriend' => $isFriend,
        ];
    }
    
}
