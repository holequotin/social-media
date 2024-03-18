<?php

namespace App\Listeners;

use App\Enums\FriendshipStatus;
use App\Events\FriendshipCreated;
use App\Notifications\FriendRequestNotification;
use App\Repositories\User\UserRepositoryInterface;

class SendFriendshipCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FriendshipCreated $event): void
    {
        $user = $this->userRepository->find($event->friendship->to_user_id);
        if($event->friendship->status == FriendshipStatus::PENDING){
            $user->notify(new FriendRequestNotification($event->friendship));
        }
    }
}
