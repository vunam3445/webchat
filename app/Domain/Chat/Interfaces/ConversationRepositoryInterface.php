<?php
// ConversationRepositoryInterface.php
namespace Domain\Chat\Interfaces;

use Domain\Chat\Entity\Conversation;
interface ConversationRepositoryInterface {
    public function CreatePrivateConversation(string $userId1, string $userId2): ?Conversation;
    public function findConversation(string $conversationId): ?Conversation;
    public function findPrivateConversationBetween(string $userId1, string $userId2): ?Conversation;


}
