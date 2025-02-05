<?php

namespace App\Services;

use App\Enums\FriendshipStatus;
use App\Models\User;
use App\Repositories\Friendship\FriendshipRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Exception;

class FriendshipService
{
    public function __construct(
        protected FriendshipRepositoryInterface $friendshipRepository,
        protected UserRepositoryInterface       $userRepository
    )
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

    public function getFriendsByUser($user, $perPage = 15)
    {
        return $this->friendshipRepository->getFriendsByUser($user, $perPage);
    }

    public function unfriend($validated)
    {
        return $this->friendshipRepository->deleteFriendship(auth()->id(), $validated['friend_id']);
    }

    public function getFriendship($userId, $friendId)
    {
        return $this->friendshipRepository->getFriendship($userId, $friendId);
    }

    public function getFriendRequest($userId, $perPage = 15)
    {
        return $this->friendshipRepository->getFriendRequest($userId, $perPage);
    }

    public function getMutualFriends(User $user1, User $user2)
    {
        return $this->userRepository->getMutualFriends($user1, $user2);
    }

    public function getSuggestionFriends(User $user)
    {
        $friendSuggestionCount = config('define.friend.suggestion');
        $users = $this->userRepository->getSuggestionFriends($user);
        $ids = $users->pluck('id')->all();
        if ($users->count() < $friendSuggestionCount) {
            $users = $users->merge($this->userRepository->getRandomSuggestionFriends($user, $ids, $friendSuggestionCount - $users->count()));
        }
        return $users;
    }

    public function setNickname(User $user, string|null $nickname)
    {
        if (!$user->checkIsFriend(auth()->id())) {
            throw new Exception(__('exception.friend.is_not_friend'));
        }
        $this->friendshipRepository->setNickname($user, $nickname);
    }
}
