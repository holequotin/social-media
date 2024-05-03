<?php

namespace App\Repositories\GroupUser;

use App\Models\Group;
use App\Models\User;
use App\Repositories\RepositoryInterface;

interface GroupUserRepositoryInterface extends RepositoryInterface
{
    public function getRequestToJoinGroup(User $user, Group $group, $perPage);
    public function setShowPostType(User $user, Group $group, $type);

    public function getMembers(Group $group);
}
