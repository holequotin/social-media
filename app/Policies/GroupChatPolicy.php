<?php

namespace App\Policies;

use App\Models\GroupChat;
use App\Models\User;
use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;

class GroupChatPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(protected GroupChatUserRepositoryInterface $groupChatUserRepository)
    {
    }

    public function delete(User $user, GroupChat $groupChat)
    {
        return $this->groupChatUserRepository->isAdmin($user->id, $groupChat->id);
    }
}
