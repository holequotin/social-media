<?php

namespace App\Repositories\GroupChat;

use App\Repositories\RepositoryInterface;

interface GroupChatRepositoryInterface extends RepositoryInterface
{
    public function getGroupChatsByUser($userId);

    public function getUsersCanAdd($groupChatId);
}
