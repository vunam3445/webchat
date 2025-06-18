<?php

namespace Application\Conversation;

use Domain\Chat\Entity\Message;
use Domain\Chat\Interfaces\MessageRepositoryInterface;

class MessageService
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository
    ) {}

    /**
     * Gửi tin nhắn mới.
     */
    public function sendMessage(
        string $conversationId,
        string $senderId,
        string $content,
        string $name,
        string $avatar,
        string $type = 'text',
        array $metadata = []
    ): Message {
        return $this->messageRepository->send(
            $conversationId,
            $senderId,
            $name,
            $avatar,
            $content,
            $type,
            $metadata
        );
    }

    /**
     * Lấy danh sách tin nhắn theo kiểu "load more":
     * - Mặc định lấy 20 tin mới nhất.
     * - Nếu truyền $beforeTimestamp, sẽ lấy các tin trước thời điểm đó.
     */
    public function getMessages(
        string $conversationId,
        ?string $beforeTimestamp = null,
        int $limit = 20
    ): array {
        return $this->messageRepository->getMessages(
            $conversationId,
            $beforeTimestamp,
            $limit
        );
    }
}
