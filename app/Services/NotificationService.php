<?php
namespace App\Services;

use App\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    public function __construct() {

    }

    public function getNotificationByUser($user, $type = 'all')
    {
        switch ($type) {
            case 'read':
                return DatabaseNotification::where('read_at','<>',null);
                break;
            case 'unread':
                return $user->unreadNotifications();
                break;
            default:
                return $user->notifications();
                break;
        }
    }
}
