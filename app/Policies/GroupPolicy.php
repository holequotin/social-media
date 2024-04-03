<?php

namespace App\Policies;

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
}
