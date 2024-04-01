<?php

namespace App\Repositories\Notification;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Notifications\DatabaseNotification;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{

    public function getModel()
    {
        return DatabaseNotification::class;
    }

    public function getUnreadNotificationCount(User $user)
    {
        return $user->unreadNotifications()->count();
    }
}
