<?php

namespace App\Providers;

use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Friendship\FriendshipRepository;
use App\Repositories\Friendship\FriendshipRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\GroupChat\GroupChatRepository;
use App\Repositories\GroupChat\GroupChatRepositoryInterface;
use App\Repositories\GroupChatUser\GroupChatUserRepository;
use App\Repositories\GroupChatUser\GroupChatUserRepositoryInterface;
use App\Repositories\GroupInvitation\GroupInvitationRepository;
use App\Repositories\GroupInvitation\GroupInvitationRepositoryInterface;
use App\Repositories\GroupUser\GroupUserRepository;
use App\Repositories\GroupUser\GroupUserRepositoryInterface;
use App\Repositories\Message\MessageRepository;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Post\PostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\PostImage\PostImageRepository;
use App\Repositories\PostImage\PostImageRepositoryInterface;
use App\Repositories\Reaction\ReactionRepository;
use App\Repositories\Reaction\ReactionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostImageRepositoryInterface::class, PostImageRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(ReactionRepositoryInterface::class, ReactionRepository::class);
        $this->app->bind(FriendshipRepositoryInterface::class, FriendshipRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(GroupUserRepositoryInterface::class, GroupUserRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(GroupInvitationRepositoryInterface::class, GroupInvitationRepository::class);
        $this->app->bind(GroupChatRepositoryInterface::class, GroupChatRepository::class);
        $this->app->bind(GroupChatUserRepositoryInterface::class, GroupChatUserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
