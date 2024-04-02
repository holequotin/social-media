<?php

namespace App\Repositories\Notification;

use App\Models\User;
use App\Repositories\RepositoryInterface;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    public function getUnreadNotificationCount(User $user);

    public function getNotificationByUser(User $user, $type, $perPage);
}
