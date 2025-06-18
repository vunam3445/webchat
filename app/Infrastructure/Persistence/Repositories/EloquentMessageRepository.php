<?php

namespace Infrastructure\Persistence\Repositories;

use Domain\Chat\Interfaces\MessageRepositoryInterface;
use Domain\Chat\Entity\Message;
use Illuminate\Support\Str;
use App\Models\Message as EloquentMessage;
use App\Models\User;

class EloquentMessageRepository implements MessageRepositoryInterface
{
    public function send(string $conversationId, string $senderId, string $name, string $avatar, string $content, string $type): Message
    {
        $message = EloquentMessage::create([
            'id' => Str::uuid(),
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'sender_name' => $name,
            'sender_avatar' => $avatar,
            'content' => $content,
            'type' => $type,
            'metadata' => [],
        ]);

        $user = User::find($senderId);

        return new Message(
            id: $message->id,
            conversationId: $conversationId,
            senderId: $senderId,
            name: $name,
            avatar: $avatar,
            content: $content,
            type: $type,
            metadata: [],
            createdAt: new \DateTime($message->created_at),
        );
    }

    public function getMessages(string $conversationId, ?string $beforeTimestamp = null, int $limit = 20): array
    {
        $query = EloquentMessage::with('sender')
            ->where('conversation_id', $conversationId);

        if ($beforeTimestamp) {
            $query->where('created_at', '<', $beforeTimestamp);
        }

        $messages = $query->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($msg) {
                return new Message(
                    id: $msg->id,
                    conversationId: $msg->conversation_id,
                    senderId: $msg->sender_id,
                    content: $msg->content,
                    type: $msg->type,
                    metadata: $msg->metadata ?? [],
                    createdAt: new \DateTime($msg->created_at),
                    name: $msg->sender?->name ?? '',
                    avatar: $msg->sender?->avatar ?? '',
                );
            })
            ->reverse() // Đảo lại để trả theo thứ tự từ cũ -> mới
            ->values()
            ->all();

        return $messages;
    }
}
