<?php

namespace App\Repositories\Friendship;

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
        $send_friends = $user->friends()->getQuery()->select('users.*');
        $be_send_friends = $user->isFriends()->getQuery()->select('users.*');
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
            ->first();
    }

    public function deleteFriendship($userId, $friendId)
    {
        return $this->getFriendship($userId, $friendId)->delete();
    }
}
