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
        $conversation = $this->conversationRepository->findPrivateConversationBetween($userId1 , $userId2);
        if (!$conversation) {
            return $this->conversationRepository->CreatePrivateConversation($userId1, $userId2);
        }
        return $conversation;
    }
}
