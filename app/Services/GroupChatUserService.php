<?php

namespace App\Services;

use App\Models\GroupChat;
use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;
use Carbon\Carbon;

class GroupChatUserService
{
    public function __construct(protected GroupChatUserRepositoryInterface $groupChatUserRepository)
    {
    }

    public function addUsersToGroupChat($validated, $role)
    {
        $usersCollection = collect($validated['users']);
        $data = $usersCollection->map(function ($user) use ($validated, $role) {
            return ['user_id' => $user, 'group_chat_id' => $validated['group_chat_id'], 'role' => (integer)$role, 'joined_at' => Carbon::now()];
        });

        return $this->groupChatUserRepository->getModel()::insert($data->all());
    }

    public function updateRole($groupChatUser, $validated)
    {
        return $this->groupChatUserRepository->update($groupChatUser->id, $validated);
    }

    public function getByGroupChat(GroupChat $groupChat)
    {
        return $this->groupChatUserRepository->getByGroupChat($groupChat->id);
    }
}
