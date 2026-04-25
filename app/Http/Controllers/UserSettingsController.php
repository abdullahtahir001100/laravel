<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserSettingsController extends Controller
{
    private const DEFAULT_SETTINGS = [
        'privateAccount' => false,
        'showEmail' => false,
        'showPhone' => false,
        'showOnlineStatus' => true,
        'searchEngineIndexing' => true,
        'profileSuggestions' => true,
        'tagReview' => false,
        'mentionReview' => false,
        'twoFactor' => true,
        'loginAlerts' => true,
        'trustedDevicesOnly' => false,
        'autoSessionTimeout' => true,
        'accountType' => 'Creator',
        'language' => 'English',
        'timezone' => 'Asia/Karachi',
        'contentLanguage' => 'Both',
        'reducedMotion' => false,
        'highContrast' => false,
        'largerText' => false,
        'keyboardNavigation' => true,
        'notifyLikes' => true,
        'notifyComments' => true,
        'notifyMentions' => true,
        'notifyFollows' => true,
        'emailDigest' => true,
        'productUpdates' => true,
        'marketingEmails' => false,
        'pushNotifications' => true,
        'saveWatchHistory' => true,
        'saveLikeHistory' => true,
        'personalizedRecommendations' => true,
        'autoplayVideos' => false,
        'likedPostsVisible' => false,
        'watchedPostsVisible' => false,
        'activityRetention' => '90 Days',
        'historySync' => true,
        'dataExportFormat' => 'ZIP',
        'includeMediaInExport' => true,
        'backupFrequency' => 'Weekly',
        'backupEmail' => 'backup@studio.com',
        'deactivationWindow' => '14 Days',
        'accountDeletionProtection' => true,
        'legalConsent' => true,
        'gdprMode' => false,
    ];

    private const PROFILE_KEYS = [
        'fullName',
        'displayName',
        'username',
        'pronouns',
        'email',
        'phone',
        'country',
        'city',
        'headline',
        'about',
        'bio',
        'website',
    ];

    public function read(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->settings === null) {
            $user->settings = self::DEFAULT_SETTINGS;
            $user->save();
        }

        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

        return response()->json([
            'profile' => [
                'fullName' => $fullName,
                'displayName' => $user->display_name ?? $fullName,
                'username' => $user->username,
                'pronouns' => $user->pronouns,
                'email' => $user->email,
                'phone' => $user->phone,
                'country' => $user->country,
                'city' => $user->city,
                'headline' => $user->headline,
                'about' => $user->about,
                'bio' => $user->bio,
                'website' => $user->website,
                'avatarUrl' => $user->avatar_path ? Storage::url($user->avatar_path) : null,
                'coverPhotoUrl' => $user->cover_photo_path ? Storage::url($user->cover_photo_path) : null,
            ],
            'settings' => array_merge(self::DEFAULT_SETTINGS, $user->settings ?? []),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->merge([
            'displayName' => $this->emptyToNull($request->input('displayName')),
            'username' => $this->emptyToNull($request->input('username')),
            'pronouns' => $this->emptyToNull($request->input('pronouns')),
            'email' => $this->emptyToNull($request->input('email')),
            'phone' => $this->emptyToNull($request->input('phone')),
            'country' => $this->emptyToNull($request->input('country')),
            'city' => $this->emptyToNull($request->input('city')),
            'headline' => $this->emptyToNull($request->input('headline')),
            'about' => $this->emptyToNull($request->input('about')),
            'bio' => $this->emptyToNull($request->input('bio')),
            'website' => $this->emptyToNull($request->input('website')),
        ]);

        $validated = $request->validate([
            'fullName' => ['nullable', 'string', 'max:255'],
            'displayName' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'pronouns' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:80'],
            'headline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'settings' => ['nullable', 'array'],
        ]);

        if (array_key_exists('fullName', $validated) && !empty(trim((string) $validated['fullName']))) {
            $parts = preg_split('/\s+/', trim($validated['fullName'] ?? ''), 2) ?: [];
            $user->first_name = $parts[0] ?? $user->first_name;
            $user->last_name = $parts[1] ?? '';
        }

        $profileColumnMap = [
            'displayName' => 'display_name',
            'username' => 'username',
            'pronouns' => 'pronouns',
            'email' => 'email',
            'phone' => 'phone',
            'country' => 'country',
            'city' => 'city',
            'headline' => 'headline',
            'about' => 'about',
            'bio' => 'bio',
            'website' => 'website',
        ];

        foreach ($profileColumnMap as $payloadKey => $column) {
            if (array_key_exists($payloadKey, $validated)) {
                $user->{$column} = $validated[$payloadKey];
            }
        }

        if (isset($validated['settings'])) {
            $sanitizedSettings = $this->sanitizeSettings($validated['settings']);
            $user->settings = array_merge(self::DEFAULT_SETTINGS, $user->settings ?? [], $sanitizedSettings);
        } elseif ($user->settings === null) {
            $user->settings = self::DEFAULT_SETTINGS;
        }

        $user->save();

        return response()->json([
            'message' => 'Settings saved successfully.',
            'user' => $this->read($request)->getData(true),
        ]);
    }

    private function sanitizeSettings(array $settings): array
    {
        $allowed = array_keys(self::DEFAULT_SETTINGS);
        return array_intersect_key($settings, array_flip($allowed));
    }

    private function emptyToNull(mixed $value): mixed
    {
        if (is_string($value) && trim($value) === '') {
            return null;
        }

        return $value;
    }

    public function updateMedia(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        if (isset($validated['profile_image'])) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = $validated['profile_image']->store('users/avatars', 'public');
        }

        if (isset($validated['cover_photo'])) {
            if ($user->cover_photo_path) {
                Storage::disk('public')->delete($user->cover_photo_path);
            }
            $user->cover_photo_path = $validated['cover_photo']->store('users/covers', 'public');
        }

        if ($user->settings === null) {
            $user->settings = self::DEFAULT_SETTINGS;
        }

        $user->save();

        return response()->json([
            'message' => 'Media updated successfully.',
            'avatarUrl' => $user->avatar_path ? Storage::url($user->avatar_path) : null,
            'coverPhotoUrl' => $user->cover_photo_path ? Storage::url($user->cover_photo_path) : null,
        ]);
    }
}
