<?php

namespace App\Policies;

use App\Enums\PostType;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function delete(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    public function show(User $user, Post $post)
    {
        $isOwner = $user->id == $post->user_id;
        $isPublic = $post->type == PostType::PUBLIC;
        $isFriend = $user->checkIsFriend($post->user_id) && $post->type == PostType::FRIENDS;

        return $isOwner || $isPublic || $isFriend;
    }
}
