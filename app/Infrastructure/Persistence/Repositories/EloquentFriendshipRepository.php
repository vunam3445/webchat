<?php

namespace Infrastructure\Persistence\Repositories;

use App\Models\FriendShip;
use Domain\Friendship\Entity\Friendship as DomainFriendship;
use Domain\Friendship\Interfaces\FriendshipRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EloquentFriendshipRepository implements FriendshipRepositoryInterface
{
    public function sendRequest(DomainFriendship $friendship): void
    {
        FriendShip::create([
            'user_id' => $friendship->getUserId(),
            'friend_id' => $friendship->getFriendId(),
            'status' => $friendship->getStatus(),
        ]);
    }

    public function acceptRequest(string $userId, string $friendId): void
    {
        Log::info("Accepting request from $userId to $friendId");
        FriendShip::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->update(['status' => 'accepted']);
    }

    public function rejectRequest(string $userId, string $friendId): void
    {
        FriendShip::where('user_id', $friendId)
            ->where('friend_id', $userId)
            ->update(['status' => 'rejected']);
    }

    public function blockUser(string $userId, string $friendId): void
    {
        FriendShip::where('user_id', $friendId)
            ->where('friend_id', $userId)
            ->update(['status' => 'blocked']);
    }
    public function getFriends(string $userId): array
    {
        return FriendShip::with(['user', 'friend']) // eager load user info
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('friend_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($friendship) use ($userId) {
                // Lấy user còn lại trong mối quan hệ
                $otherUser = $friendship->user_id === $userId
                    ? $friendship->friend
                    : $friendship->user;

                return [
                    'id'     => $otherUser->id,
                    'name'   => $otherUser->name,
                    'avatar' => $otherUser->avatar,
                ];
            })
            ->all();
    }


    public function getPendingRequests(string $friendId): array
    {
        return FriendShip::with('user') // eager load sender
            ->where('friend_id', $friendId)
            ->where('status', 'pending')
            ->get()
            ->map(function ($friendship) {
                return [
                    'user_id' => $friendship->user->id ?? null,
                    'name' => $friendship->user->name ?? 'Không rõ',
                    'avatar' => $friendship->user->avatar ?? null,
                ];
            })
            ->all();
    }

    protected function toDomain(FriendShip $model): DomainFriendship
    {
        return new DomainFriendship(
            $model->user_id,
            $model->friend_id,
            $model->status
        );
    }
}
