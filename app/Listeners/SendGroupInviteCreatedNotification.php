<?php

namespace App\Listeners;

use App\Events\GroupInvitationCreated;
use App\Notifications\GroupInviteNotification;

class SendGroupInviteCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GroupInvitationCreated $event): void
    {
        $event->groupInvitation->beInvite->notify(new GroupInviteNotification($event));
    }
}
