<?php

namespace App\Repositories\GroupChatUser;

use App\Models\GroupChatUser;
use App\Repositories\BaseRepository;

class GroupChatUserRepository extends BaseRepository implements GroupChatUserRepositoryInterface
{

    public function getModel()
    {
        return GroupChatUser::class;
    }
}
