<?php

namespace App\Repositories\GroupChatUser;

use App\Repositories\RepositoryInterface;

interface GroupChatUserRepositoryInterface extends RepositoryInterface
{
    public function isAdmin($userId, $groupChatId);

    public function isInGroupChat($userId, $groupChatId);
}
