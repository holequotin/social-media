<?php

namespace App\Repositories\GroupChatUser;

use App\Repositories\RepositoryInterface;

interface GroupChatUserRepositoryInterface extends RepositoryInterface
{
    public function isAdmin($userId, $groupChatId);
    public function isInGroupChat($userId, $groupChatId);
    public function getByGroupChat($groupChatId);

    public function remove($userId, $groupChatId);

    public function hasAnotherAdmin($userId, $groupChatId);
}
