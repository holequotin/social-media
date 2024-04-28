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
    public function getGroupsByName($name, $perPage = 15);
    public function getJoinGroupStatus(Group $group, User $user);

    public function getGroupBySlug(string $slug);
}
