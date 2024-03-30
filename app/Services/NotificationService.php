<?php
namespace App\Services;

use App\Enums\NotificationStatus;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    public function __construct(protected NotificationRepositoryInterface $notificationRepository)
    {

    }

    public function getNotificationByUser($user, $type = 'all')
    {
        switch ($type) {
            case NotificationStatus::READ:
                return DatabaseNotification::where('read_at','<>',null);
            case NotificationStatus::NOT_READ:
                return $user->unreadNotifications();
            default:
                return $user->notifications();
        }
    }

    public function getUnreadNotificationCount($user)
    {
        return $this->notificationRepository->getUnreadNotificationCount($user);
    }
}
