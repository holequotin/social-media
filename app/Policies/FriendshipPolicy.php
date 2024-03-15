<?php

namespace App\Policies;

use App\Models\Friendship;
use App\Models\User;

class FriendshipPolicy
{
    public function accept(User $user, Friendship $friendship): bool
    {
        return $user->id == $friendship->to_user_id;
    }
}
