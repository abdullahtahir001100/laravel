<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_type',
        'title',
        'subtitle',
        'description',
        'tags',
        'visibility',
        'media_path',
        'media_type',
        'status',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ContentLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ContentComment::class);
    }

    public function interests(): HasMany
    {
        return $this->hasMany(ContentInterest::class);
    }
}
