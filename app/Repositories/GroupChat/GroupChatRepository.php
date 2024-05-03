<?php

namespace App\Repositories\GroupChat;

use App\Models\GroupChat;
use App\Repositories\BaseRepository;

class GroupChatRepository extends BaseRepository implements GroupChatRepositoryInterface
{

    public function getModel()
    {
        return GroupChat::class;
    }
}
