<?php
namespace App\Repositories\Friendship;

use App\Repositories\RepositoryInterface;

interface FriendshipRepositoryInterface extends RepositoryInterface
{
    public function getFriendsByUser($user);
    public function getFriendship($userId, $friendId);
    public function deleteFriendship($userId, $friendId);
}
