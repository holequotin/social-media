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

    public function getFriendsByUser($user, $perPage = 15)
    {
        if ($user->is(auth()->user())) {
            $selectSend = ['users.*', 'friendships.to_user_nickname as nickname'];
            $selectBeSend = ['users.*', 'friendships.from_user_nickname as nickname'];
        } else {
            $selectSend = $selectBeSend = ['users.*'];
        }
        $send_friends = $user->friends()->where('status', FriendshipStatus::ACCEPTED)->getQuery()->select(...$selectSend);
        $be_send_friends = $user->isFriends()->where('status', FriendshipStatus::ACCEPTED)->getQuery()->select(...$selectBeSend);
        return $send_friends->union($be_send_friends)->paginate($perPage);
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

    public function getFriendRequest($userId, $perPage)
    {
        return $this->getModel()::where('to_user_id', $userId)
            ->where('status', FriendshipStatus::PENDING)
            ->with(['fromUser'])
            ->paginate($perPage);
    }
}
