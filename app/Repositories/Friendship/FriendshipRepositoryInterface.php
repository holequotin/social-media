<?php
namespace App\Repositories\Friendship;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface FriendshipRepositoryInterface extends RepositoryInterface
{
    public function getFriendsByUser($user, $perPage);
    public function getFriendship($userId, $friendId);
    public function deleteFriendship($userId, $friendId);
    public function getFriendRequest($userId, $perPage);
    public function setNickname(User $user, string|null $nickname);
}
