<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message
    ) {
        $this->message->load(['user', 'conversation']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'user_id' => $this->message->user_id,
            'sender_name' => $this->message->user->first_name . ' ' . $this->message->user->last_name,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}
