<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Group;
use App\Models\Post;
use App\Models\Reaction;
use App\Policies\CommentPolicy;
use App\Policies\FriendshipPolicy;
use App\Policies\GroupPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PostPolicy;
use App\Policies\ReactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Reaction::class => ReactionPolicy::class,
        DatabaseNotification::class => NotificationPolicy::class,
        Friendship::class => FriendshipPolicy::class,
        Group::class => GroupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
