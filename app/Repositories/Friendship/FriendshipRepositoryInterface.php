<?php
namespace App\Repositories\Friendship;

use App\Repositories\RepositoryInterface;

interface FriendshipRepositoryInterface extends RepositoryInterface
{
    public function getFriendsByUser($user);
}
