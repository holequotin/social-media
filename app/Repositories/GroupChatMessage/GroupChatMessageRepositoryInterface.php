<?php

namespace App\Repositories\GroupChatMessage;

use App\Models\GroupChat;
use App\Repositories\RepositoryInterface;

interface GroupChatMessageRepositoryInterface extends RepositoryInterface
{
    public function getMessageByGroupChat(GroupChat $groupChat);
}
