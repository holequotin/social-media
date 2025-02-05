<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\FriendshipCreated;
use App\Events\GroupInvitationCreated;
use App\Listeners\SendCommentedNotification;
use App\Listeners\SendFriendshipCreatedNotification;
use App\Listeners\SendGroupInviteCreatedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(CommentCreated::class, SendCommentedNotification::class);
        Event::listen(FriendshipCreated::class, SendFriendshipCreatedNotification::class);
        Event::listen(GroupInvitationCreated::class, SendGroupInviteCreatedNotification::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
