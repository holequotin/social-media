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
}
