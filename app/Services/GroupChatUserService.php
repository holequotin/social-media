<?php

namespace App\Services;

use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;
use Carbon\Carbon;

class GroupChatUserService
{
    public function __construct(protected GroupChatUserRepositoryInterface $groupChatUserRepository)
    {
    }

    public function addUsersToGroupChat($userIds, $groupChatId, $role)
    {
        $usersCollection = collect($userIds);
        $data = $usersCollection->map(function ($user) use ($groupChatId, $role) {
            return ['user_id' => $user, 'group_chat_id' => $groupChatId, 'role' => (integer)$role, 'joined_at' => Carbon::now()];
        });

        return $this->groupChatUserRepository->getModel()::insert($data->all());
    }
}
