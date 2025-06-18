<?php
namespace Domain\Chat\Entity;

class Message
{
    public function __construct(
        public string $id,
        public string $conversationId,
        public string $senderId,
        public string $name,
        public string $avatar,
        public string $content,
        public string $type, // 'text', 'image',...
        public array $metadata = [],
        public \DateTime $createdAt
    ) {}
}
