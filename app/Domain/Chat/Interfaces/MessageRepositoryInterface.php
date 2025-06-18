<?php
// MessageRepositoryInterface.php
namespace Domain\Chat\Interfaces;
interface MessageRepositoryInterface {
    public function send(string $conversationId, string $senderId,string $name,string $avatar, string $content, string $type): \Domain\Chat\Entity\Message;
    public function getMessages(string $conversationId): array;
}
