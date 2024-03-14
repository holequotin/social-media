<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Notifications\CommentCreatedNotification;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentedNotification implements ShouldQueue
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
    public function handle(CommentCreated $event): void
    {
        $user = $this->userRepository->find($event->comment->post->user_id);
        if ($event->comment->post->user_id != $event->comment->user_id) {
            $user->notify(new CommentCreatedNotification($event->comment));
        }
    }
}
