<?php

namespace App\Policies;

use App\Enums\JoinGroupStatus;
use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Group $group)
    {
        return $user->id == $group->owner_id;
    }

    public function delete(User $user, Group $group)
    {
        return $user->id == $group->owner_id;
    }

    public function removeUser(User $user, Group $group, User $removedUser)
    {
        return $user->is($group->owner) && $removedUser->groups->contains($group);
    }

    public function acceptUser(User $user, Group $group, User $acceptedUser)
    {
        return $user->is($group->owner) && $acceptedUser->groups()
                ->wherePivot('group_id', $group->id)
                ->wherePivot('status', JoinGroupStatus::WAITING)
                ->exists();

    }

    public function getPosts(User $user, Group $group)
    {
        return $user->isInGroup($group);
    }

    public function getUsers(User $user, Group $group)
    {
        return $user->isInGroup($group);
    }
}
