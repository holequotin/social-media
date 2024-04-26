<?php
namespace App\Repositories\User;

use App\Models\Group;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getUserByEmail($email);
    public function getUserByName($name, $perPage);
    public function getUsersInGroup(Group $group, $perPage);
    public function isInGroup(Group $group, User $user);
    public function isWaitingAcceptGroup(Group $group, User $user);
    public function getGroupsByUser(User $user, $perPage);
    public function getMutualFriends(User $user1, User $user2);
    public function getSuggestionFriends(User $user);
    public function getRandomSuggestionFriends(User $user, $ids, $number);
}
