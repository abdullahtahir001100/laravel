<?php

namespace App\Http\Controllers;

use App\Models\ContentItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContentItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = ContentItem::query()
            ->where('user_id', $request->user()->id)
            ->latest('published_at')
            ->latest('id')
            ->get()
            ->map(fn (ContentItem $item) => $this->transformItem($item));

        return response()->json(['items' => $items]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content_type' => ['required', Rule::in(['post', 'reel', 'live'])],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'visibility' => ['required', Rule::in(['public', 'private'])],
            'media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm,m4v,mpeg,avi,mkv,flv,3gp', 'max:1048576'],
        ]);

        $media = $request->file('media');
        $mediaType = null;
        $mediaPath = null;

        if ($media) {
            $mimeType = (string) $media->getMimeType();
            // Check if it's a video by MIME type or file extension
            $isVideo = str_starts_with($mimeType, 'video/') || in_array(strtolower($media->getClientOriginalExtension()), ['mp4', 'mov', 'webm', 'm4v', 'mpeg', 'avi', 'mkv', 'flv', '3gp']);
            $mediaType = $isVideo ? 'video' : 'image';
            
            try {
                $mediaPath = $media->store('content-items/' . $request->user()->id, 'public');
                
                if (!$mediaPath) {
                    return response()->json([
                        'message' => 'Failed to store media file',
                        'error' => 'Storage path generation failed'
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload media',
                    'error' => $e->getMessage()
                ], 422);
            }
        }

        $tags = collect(explode(',', (string) ($validated['tags'] ?? '')))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        $item = ContentItem::create([
            'user_id' => $request->user()->id,
            'content_type' => $validated['content_type'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'tags' => $tags,
            'visibility' => $validated['visibility'],
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'status' => 'published',
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Content published successfully.',
            'item' => $this->transformItem($item),
        ]);
    }

    public function updateVisibility(Request $request, ContentItem $contentItem): JsonResponse
    {
        abort_unless($contentItem->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'visibility' => ['required', Rule::in(['public', 'private'])],
        ]);

        $contentItem->update(['visibility' => $validated['visibility']]);

        return response()->json([
            'message' => 'Visibility updated successfully.',
            'item' => $this->transformItem($contentItem->fresh()),
        ]);
    }

    public function destroy(Request $request, ContentItem $contentItem): JsonResponse
    {
        abort_unless($contentItem->user_id === $request->user()->id, 403);

        if ($contentItem->media_path) {
            Storage::disk('public')->delete($contentItem->media_path);
        }

        $contentItem->delete();

        return response()->json(['message' => 'Content deleted successfully.']);
    }

    private function transformItem(ContentItem $item): array
    {
        $preview = $item->media_path ? Storage::url($item->media_path) : null;

        return [
            'id' => $item->id,
            'type' => $item->content_type,
            'title' => $item->title,
            'subtitle' => $item->subtitle,
            'description' => $item->description,
            'tags' => $item->tags ?? [],
            'visibility' => $item->visibility,
            'mediaUrl' => $preview,
            'mediaType' => $item->media_type,
            'publishedAt' => optional($item->published_at ?? $item->created_at)->diffForHumans(),
        ];
    }
}
