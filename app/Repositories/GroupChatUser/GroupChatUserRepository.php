<?php

namespace App\Repositories\GroupChatUser;

use App\Enums\GroupChatRole;
use App\Models\GroupChatUser;
use App\Repositories\BaseRepository;

class GroupChatUserRepository extends BaseRepository implements GroupChatUserRepositoryInterface
{

    public function getModel()
    {
        return GroupChatUser::class;
    }

    public function isAdmin($userId, $groupChatId)
    {
        return $this->getModel()::where('user_id', $userId)
            ->where('group_chat_id', $groupChatId)
            ->where('role', GroupChatRole::ADMIN)
            ->exists();
    }

    public function isInGroupChat($userId, $groupChatId)
    {
        return $this->getModel()::where('user_id', $userId)
            ->where('group_chat_id', $groupChatId)
            ->exists();
    }
}
