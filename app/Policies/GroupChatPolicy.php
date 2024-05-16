<?php

namespace App\Policies;

use App\Models\GroupChat;
use App\Models\User;
use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;
use Illuminate\Auth\Access\Response;

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

    public function getMessages(User $user, GroupChat $groupChat)
    {
        return $this->groupChatUserRepository->isInGroupChat($user->id, $groupChat->id);
    }

    public function view(User $user, GroupChat $groupChat)
    {
        return $this->groupChatUserRepository->isInGroupChat($user->id, $groupChat->id);
    }

    public function leave(User $user, GroupChat $groupChat)
    {
        if (!$this->groupChatUserRepository->isInGroupChat($user->id, $groupChat->id)) {
            return Response::deny(__('common.group_chat.not_in'));
        }

        if (!$this->groupChatUserRepository->hasAnotherAdmin($user->id, $groupChat->id)) {
            return Response::deny(__('common.group_chat.not_has_admin'));
        }

        return true;

    }
}
