<?php

namespace Infrastructure\Persistence\Repositories;

use Domain\Chat\Entity\Conversation;
use Domain\Chat\Interfaces\ConversationRepositoryInterface;
use Illuminate\Support\Str;
use App\Models\Conversation as EloquentConversation;
use App\Models\ConversationUser;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Domain\Friendship\Entity\Friendship as DomainFriendship;


class ElquentConversationRepository implements ConversationRepositoryInterface
{
    public function CreatePrivateConversation(string $userId1, string $userId2): ?Conversation
    {

        Log::info('CreatePrivateConversation called', ['user1' => $userId1, 'user2' => $userId2]);

        if (!User::where('id', $userId1)->exists() || !User::where('id', $userId2)->exists()) {
            throw new \Exception("Một hoặc cả hai user không tồn tại.");
        }
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
        $conversation = EloquentConversation::with('users')->find($conversationId);
        if (!$conversation) return null;

        // Dữ liệu người dùng (id, name, avatar)
        $participants = $conversation->users->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            
        ])->toArray();

        $name = $conversation->type === 'private'
            ? $conversation->users->firstWhere('id', '!=', Auth::id())?->name ?? 'Unknown'
            : ($conversation->name ?? 'Group Chat');

        return new Conversation(
            id: $conversation->id,
            type: $conversation->type,
            name: $name,
            createdBy: $conversation->created_by,
            participants: $participants // trả về chi tiết thay vì chỉ id
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
        $friend = $conversation->users->firstWhere('id', '!=', $userId1);
        $name = $friend?->name ?? 'Unknown';
        return new Conversation(
            id: $conversation->id,
            type: $conversation->type,
            name: $name,
            participants: $participantIds
        );
    }

    public function createGroupConversation(string $name, array $userIds): ?Conversation
    {
        $conversation = EloquentConversation::create([
            'id' => Str::uuid(),
            'type' => 'group',
            'name' => $name,
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $members = [];
        foreach ($userIds as $userId) {
            $members[] = [
                'id' => Str::uuid(),
                'conversation_id' => $conversation->id,
                'user_id' => $userId,
                'joined_at' => now(),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ConversationUser::insert($members);

        return new Conversation(
            id: $conversation->id,
            type: 'group',
            name: $conversation->name,
            participants: $userIds
        );
    }


    public function addMemberToGroup(string $conversationId, array $userIds): ?Conversation
    {
        foreach ($userIds as $userId) {
            $exists = ConversationUser::where('conversation_id', $conversationId)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {
                ConversationUser::create([
                    'id' => Str::uuid(),
                    'conversation_id' => $conversationId,
                    'user_id' => $userId,
                    'joined_at' => now(),
                    'role' => 'member',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $this->findConversation($conversationId);
    }

    public function removeMemberFromGroup(string $conversationId, string $userId): ?Conversation
    {
        ConversationUser::where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->delete();

        return $this->findConversation($conversationId);
    }

    public function getConversationsByUserId(string $userId): array
    {
        $conversations = EloquentConversation::with(['users', 'latestMessage'])
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get()
            ->sortByDesc(fn($conversation) => optional($conversation->latestMessage)->created_at);

        $result = [];

        foreach ($conversations as $conversation) {
            if ($conversation->type === 'private') {
                $friend = $conversation->users->firstWhere('id', '!=', $userId);
                if (!$friend) continue;

                $result[] = [
                    'id' => $friend->id, // Trả về ID của bạn (friend)
                    'type' => 'private',
                    'name' => $friend->name,
                    'avatar' => $friend->avatar,
                ];
            } else {
                $result[] = [
                    'id' => $conversation->id,
                    'type' => 'group',
                    'name' => $conversation->name ?? 'Group Chat',
                    'conversation_id' => $conversation->id,
                    // 'avatar' => $conversation->avatar ?? 'group-default.png'
                ];
            }
        }

        return array_values($result);
    }
}
