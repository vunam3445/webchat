<?php

namespace Infrastructure\Persistence\Repositories;

use Domain\Chat\Entity\Conversation;
use Domain\Chat\Interfaces\ConversationRepositoryInterface;
use Illuminate\Support\Str;
use App\Models\Conversation as EloquentConversation;
use App\Models\ConversationUser;

class ElquentConversationRepository implements ConversationRepositoryInterface
{
    public function CreatePrivateConversation(string $userId1, string $userId2): ?Conversation
    {
        // Tạo mới conversation
        $conversation = EloquentConversation::create([
            'id' => Str::uuid(),
            'type' => 'private',
            'created_by' => $userId1,
        ]);

        // Gắn 2 user vào bảng trung gian
        $members = [
            [
                'id' => Str::uuid(),
                'conversation_id' => $conversation->id,
                'user_id' => $userId1,
                'joined_at' => now(),
                'role' => 'member',
            ],
            [
                'id' => Str::uuid(),
                'conversation_id' => $conversation->id,
                'user_id' => $userId2,
                'joined_at' => now(),
                'role' => 'member',
            ]
        ];
        ConversationUser::insert($members);

        return new Conversation(
            id: $conversation->id,
            type: $conversation->type,
            participants: [$userId1, $userId2]
        );
    }

    public function findConversation(string $conversationId): ?Conversation
    {
        $conversation = EloquentConversation::find($conversationId);
        if (!$conversation) return null;

        $userIds = $conversation->participants()->pluck('users.id')->toArray();

        return new Conversation(
            id: $conversation->id,
            type: $conversation->type,
            participants: $userIds
        );
    }

    // ConversationRepository.php
    public function findPrivateConversationBetween(string $userId1, string $userId2): ?Conversation
    {
        // Lấy ID của các cuộc hội thoại private mà user1 tham gia
        $conversationIdsUser1 = \App\Models\ConversationUser::where('user_id', $userId1)
            ->pluck('conversation_id')
            ->toArray();

        // Lọc các cuộc trò chuyện đó có user2 tham gia và là loại private
        $conversation = \App\Models\Conversation::whereIn('id', $conversationIdsUser1)
            ->where('type', 'private')
            ->whereHas('users', function ($query) use ($userId2) {
                $query->where('users.id', $userId2);
            })
            ->first();

        if (!$conversation) return null;

        $participantIds = $conversation->participants()->pluck('users.id')->toArray();

        return new Conversation(
            id: $conversation->id,
            type: $conversation->type,
            participants: $participantIds
        );
    }
}
