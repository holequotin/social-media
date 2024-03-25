<?php

namespace App\Repositories\Friendship;

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Repositories\BaseRepository;

class FriendshipRepository extends BaseRepository implements FriendshipRepositoryInterface
{
    public function getModel()
    {
        return Friendship::class;
    }

    public function getFriendsByUser($user)
    {
        $send_friends = $user->friends()->where('status', FriendshipStatus::ACCEPTED)->getQuery()->select('users.*');
        $be_send_friends = $user->isFriends()->where('status', FriendshipStatus::ACCEPTED)->getQuery()->select('users.*');
        return $send_friends->union($be_send_friends);
    }

    public function getFriendship($userId, $friendId)
    {
        return $this->getModel()::where('from_user_id', $userId)
            ->where('to_user_id', $friendId)
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('from_user_id', $friendId)
                    ->where('to_user_id', $userId);
            })
            ->with(['fromUser', 'toUser'])
            ->first();
    }

    public function deleteFriendship($userId, $friendId)
    {
        return $this->getFriendship($userId, $friendId)->delete();
    }

    public function getFriendRequest($userId)
    {
        return $this->getModel()::where('to_user_id', $userId)
            ->where('status', FriendshipStatus::PENDING)
            ->with(['fromUser']);
    }
}
