<?php
namespace Domain\Friendship\Interfaces;

use Domain\Friendship\Entity\Friendship;

interface FriendshipRepositoryInterface
{
    public function sendRequest(Friendship $friendship): void;
    public function acceptRequest(string $userId, string $friendId): void;
    public function rejectRequest(string $userId, string $friendId): void;
    public function blockUser(string $userId, string $friendId): void;
    public function getFriends(string $userId): array;
    public function getPendingRequests(string $userId): array;
}
