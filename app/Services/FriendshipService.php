<?php

namespace App\Services;

use App\Enums\FriendshipStatus;
use App\Repositories\Friendship\FriendshipRepositoryInterface;

class FriendshipService
{
    public function __construct(protected FriendshipRepositoryInterface $friendshipRepository)
    {
    }

    public function storeFriendRequest($validated)
    {
        $validated['from_user_id'] = auth()->id();
        return $this->friendshipRepository->create($validated);
    }

    public function acceptFriendRequest($friendship)
    {
        if ($friendship->status != FriendshipStatus::ACCEPTED) {
            $friendship = $this->friendshipRepository->update($friendship->id,['status' => FriendshipStatus::ACCEPTED]);
        }
        return $friendship;
    }

    public function getFriendsByUser($user)
    {
        return $this->friendshipRepository->getFriendsByUser($user);
    }

    public function unfriend($validated)
    {
        return $this->friendshipRepository->deleteFriendship(auth()->id(), $validated['friend_id']);
    }

    public function getFriendship($userId, $friendId)
    {
        return $this->friendshipRepository->getFriendship($userId, $friendId);
    }

    public function getFriendRequest($userId)
    {
        return $this->friendshipRepository->getFriendRequest($userId);
    }
}
