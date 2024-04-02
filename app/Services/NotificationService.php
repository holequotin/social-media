<?php
namespace App\Services;

use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(protected NotificationRepositoryInterface $notificationRepository)
    {

    }

    public function getNotificationByUser($user, $type = 'all', $perPage = 15)
    {
        return $this->notificationRepository->getNotificationByUser($user, $type, $perPage);
    }

    public function getUnreadNotificationCount($user)
    {
        return $this->notificationRepository->getUnreadNotificationCount($user);
    }

    public function markAllAsRead($user)
    {
        $user->unreadNotifications->markAsRead();
    }
}
