<?php

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;

class ReactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Reaction $reaction)
    {
        return $reaction->user_id == $user->id;
    }

    public function delete(User $user, Reaction $reaction)
    {
        return $reaction->user_id == $user->id;
    }
}
