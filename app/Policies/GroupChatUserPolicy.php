<?php

namespace App\Policies;

use App\Models\GroupChatUser;
use App\Models\User;
use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;

class GroupChatUserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(protected GroupChatUserRepositoryInterface $groupChatUserRepository)
    {
        //
    }

    public function delete(User $user, GroupChatUser $groupChatUser)
    {
        return $user->id === $groupChatUser->user_id || $this->groupChatUserRepository->isAdmin($user->id, $groupChatUser->group_chat_id);
    }
}
