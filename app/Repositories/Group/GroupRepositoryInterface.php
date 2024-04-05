<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface GroupRepositoryInterface extends RepositoryInterface
{
    public function joinGroup(Group $group, User $user);
    public function leaveGroup(Group $group, User $user);
    public function requestToJoinGroup(Group $group, User $user);
    public function getGroupsByUser(User $user, $perPage);
    public function isInGroup(Group $group, User $user);
    public function isWaitingAcceptGroup(Group $group, User $user);
}
