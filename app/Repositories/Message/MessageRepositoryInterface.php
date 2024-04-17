<?php

namespace App\Repositories\Message;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface MessageRepositoryInterface extends RepositoryInterface
{
    public function getMessagesBetweenUsers(User $user1, User $user2);

    public function getLastMessages(User $user);
}
