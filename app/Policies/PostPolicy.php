<?php

namespace App\Policies;

use App\Enums\GroupType;
use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        //
    }

    public function update(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function delete(User $user, Post $post)
    {
        $isGroupOwner = false;
        $isGroupAdmin = false;

        if ($post->group) {
            $isGroupOwner = $user->is($post->group->owner);
            $isGroupAdmin = $this->userRepository->isAdmin($post->group, $user);
        }

        return $user->id == $post->user_id || $isGroupAdmin || $isGroupOwner;
    }

    public function show(User $user, Post $post)
    {
        $isOwner = $user->id == $post->user_id;
        $isPublic = $post->type == PostType::PUBLIC;
        $isFriend = $user->checkIsFriend($post->user_id) && $post->type == PostType::FRIENDS;

        return $isOwner || $isPublic || $isFriend;
    }

    public function share(User $user, Post $post)
    {
        $isInPublicGroup = $post?->group?->type == GroupType::PUBLIC;
        $isNotInGroup = $post->group === null;
        return $isNotInGroup || $isInPublicGroup;
    }
}
