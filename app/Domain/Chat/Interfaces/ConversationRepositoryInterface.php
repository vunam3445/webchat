<?php
// ConversationRepositoryInterface.php
namespace Domain\Chat\Interfaces;

use Domain\Chat\Entity\Conversation;
interface ConversationRepositoryInterface {
    public function CreatePrivateConversation(string $userId1, string $userId2): ?Conversation;
    public function findConversation(string $conversationId);
    public function findPrivateConversationBetween(string $userId1, string $userId2): ?Conversation;
    public function createGroupConversation(string $name, array $userId): ?Conversation;
    public function addMemberToGroup(string $conversationId, array $userIds): ?Conversation;
    public function removeMemberFromGroup(string $conversationId, string $userId): ?Conversation;
    public function getConversationsByUserId(string $userId): array;
}
