<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'body',
        'delivered_at',
        'read_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function scopeBetweenUsers(Builder $query, int $firstUserId, int $secondUserId): Builder
    {
        return $query->where(function (Builder $builder) use ($firstUserId, $secondUserId) {
            $builder->where('sender_id', $firstUserId)
                ->where('recipient_id', $secondUserId);
        })->orWhere(function (Builder $builder) use ($firstUserId, $secondUserId) {
            $builder->where('sender_id', $secondUserId)
                ->where('recipient_id', $firstUserId);
        });
    }

    public function getStatusAttribute(): string
    {
        if ($this->read_at) {
            return 'read';
        }

        if ($this->delivered_at) {
            return 'delivered';
        }

        return 'sent';
    }
}
