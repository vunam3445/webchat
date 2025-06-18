<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Domain\Chat\Entity\Message;

class MessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public Message $message;
    public $conversationId;

    public function __construct(Message $message, string $conversationId)
    {
        $this->message = $message;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn()
    {
        return ['chat.' . $this->conversationId];
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->senderId,
            'content' => $this->message->content,
            'name' => $this->message->name,
            'avatar' => $this->message->avatar,
            'created_at' => $this->message->createdAt->format('H:i'),
        ];
    }
}
