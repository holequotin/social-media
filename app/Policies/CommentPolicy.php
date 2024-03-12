<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Repositories\Post\PostRepositoryInterface;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(protected PostRepositoryInterface $postRepository)
    {
        //
    }

    public function delete(User $user, Comment $comment)
    {
        $post = $this->postRepository->find($comment->post_id);
        return $post->user_id == $user->id || $comment->user_id == $user->id;
    }
}
