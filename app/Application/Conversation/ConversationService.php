<?php

namespace Application\Conversation;

use Domain\Chat\Interfaces\ConversationRepositoryInterface;
use Domain\Chat\Entity\Conversation;

class ConversationService
{
    private ConversationRepositoryInterface $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }


    public function create(string $userId1, string $userId2): ?Conversation
    {
        if ($userId1 === $userId2) {
            // Không tạo cuộc trò chuyện với chính mình
            return null;
        }
        $conversation = $this->conversationRepository->findPrivateConversationBetween($userId1, $userId2);
        if (!$conversation) {
            return $this->conversationRepository->CreatePrivateConversation($userId1, $userId2);
        }
        return $conversation;
    }
    //  Tạo nhóm mới
    public function createGroup(string $groupName, array $userIds): ?Conversation
    {
        if (empty($groupName)) {
            return null; // nhóm cần tối thiểu 2 thành viên
        }

        return $this->conversationRepository->createGroupConversation($groupName, $userIds);
    }

    //  Thêm thành viên vào nhóm
    public function addMembersToGroup(string $conversationId, array $userIds): ?Conversation
    {
        if (empty($userIds)) {
            return null;
        }

        return $this->conversationRepository->addMemberToGroup($conversationId, $userIds);
    }
    //  Xoá thành viên khỏi nhóm
    public function removeMemberFromGroup(string $conversationId, string $userId): ?Conversation
    {
        return $this->conversationRepository->removeMemberFromGroup($conversationId, $userId);
    }

    //  Lấy thông tin nhóm theo ID
    public function findConversationById(string $conversationId): ?Conversation
    {
        return $this->conversationRepository->findConversation($conversationId);
    }
    public function getConversationsByUserId(string $userId): array
    {
        return $this->conversationRepository->getConversationsByUserId($userId);
    }
}
