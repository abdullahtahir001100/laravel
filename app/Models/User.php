<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'display_name',
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
        'avatar_path',
        'cover_photo_path',
        'settings',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function contentItems(): HasMany
    {
        return $this->hasMany(ContentItem::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'following_id', 'follower_id')
            ->withPivot(['status', 'accepted_at'])
            ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'follows', 'follower_id', 'following_id')
            ->withPivot(['status', 'accepted_at'])
            ->withTimestamps();
    }

    public function sentFollowRequests(): HasMany
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function receivedFollowRequests(): HasMany
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class, 'recipient_id');
    }
}