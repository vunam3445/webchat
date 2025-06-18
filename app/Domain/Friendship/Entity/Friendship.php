<?php
namespace Domain\Friendship\Entity;

class Friendship
{
    public function __construct(
        private string $userId,
        private string $friendId,
        private string $status = 'pending'
    ) {}

    public function getUserId(): string { return $this->userId; }
    public function getFriendId(): string { return $this->friendId; }
    public function getStatus(): string { return $this->status; }

    public function accept(): void { $this->status = 'accepted'; }
    public function reject(): void { $this->status = 'rejected'; }
    public function block(): void { $this->status = 'blocked'; }


}