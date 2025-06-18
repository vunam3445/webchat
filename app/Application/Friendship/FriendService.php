<?php

namespace Application\Friendship;

use Domain\Friendship\Interfaces\FriendshipRepositoryInterface;
use Domain\Friendship\Entity\Friendship;

class FriendService
{
    private FriendshipRepositoryInterface $friendshipRepository;

    public function __construct(FriendshipRepositoryInterface $repo)
    {
        $this->friendshipRepository = $repo;
    }

    public function sendFriendRequest(string $userId, string $friendId): void
    {
        $friendship = new Friendship($userId, $friendId);
        $this->friendshipRepository->sendRequest($friendship);
    }

    public function acceptRequest(string $userId, string $friendId): void
    {
        $this->friendshipRepository->acceptRequest($userId, $friendId);
    }

    public function rejectRequest(string $userId, string $friendId): void
    {
        $this->friendshipRepository->rejectRequest($userId, $friendId);
    }
    public function blockUser(string $userId, string $friendId): void
    {
        $this->friendshipRepository->blockUser($userId, $friendId);
    }
    public function getFriends(string $userId): array
    {
        return $this->friendshipRepository->getFriends($userId);
    }

    public function getPendingRequests(string $userId): array
    {
        return $this->friendshipRepository->getPendingRequests($userId);
    }
}
