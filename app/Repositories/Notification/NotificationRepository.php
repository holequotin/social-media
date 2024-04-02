<?php

namespace App\Repositories\Notification;

use App\Enums\NotificationStatus;
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

    public function getNotificationByUser(User $user, $type, $perPage)
    {
        switch ($type) {
            case NotificationStatus::READ:
                return DatabaseNotification::where('read_at', '<>', null)->paginate($perPage);
            case NotificationStatus::NOT_READ:
                return $user->unreadNotifications()->paginate($perPage);
            default:
                return $user->notifications()->paginate($perPage);
        }
    }
}
