<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepository;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function getModel()
    {
        return Group::class;
    }

    public function joinGroup(Group $group, User $user)
    {
        if (!$user->groups->contains($group)) {
            $user->groups()->attach($group->id);
        }
    }

    public function leaveGroup(Group $group, User $user)
    {
        if ($user->groups->contains($group)) {
            $user->groups()->detach($group->id);
        }
    }
}
