<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends BaseApiController
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->perPage;
        $type = $request->type;
        $notifications = $this->notificationService->getNotificationByUser(auth()->user(), $type)->paginate($perPage);
        $addedData = [
            "unread_count" => $this->notificationService->getUnreadNotificationCount(auth()->user()),
        ];
        return $this->sendPaginateResponse(NotificationResource::collection($notifications), $addedData);
    }

    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        $this->authorize('update', $notification);
        $notification->markAsRead();
        return $this->sendResponse([
            'message' => __('common.notification.mark_as_read'),
            'notification' => NotificationResource::make($notification)
        ]);
    }
}
