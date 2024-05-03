<?php

namespace App\Repositories\GroupChatMessage;

use App\Models\GroupChatMessage;
use App\Repositories\BaseRepository;

class GroupChatMessageRepository extends BaseRepository implements GroupChatMessageRepositoryInterface
{

    public function getModel()
    {
        return GroupChatMessage::class;
    }
}
