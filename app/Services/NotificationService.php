<?php
namespace App\Services;

use App\Enums\NotificationStatus;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    public function __construct() {

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
}
