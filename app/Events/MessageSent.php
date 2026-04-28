<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    public function __construct(public array $message)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel($this->conversationChannel())];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }

    private function conversationChannel(): string
    {
        $firstUserId = (int) ($this->message['sender_id'] ?? 0);
        $secondUserId = (int) ($this->message['recipient_id'] ?? 0);
        $low = min($firstUserId, $secondUserId);
        $high = max($firstUserId, $secondUserId);

        return 'chat.' . $low . '.' . $high;
    }
}
