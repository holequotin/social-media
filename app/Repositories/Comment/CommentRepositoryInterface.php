<?php
namespace App\Repositories\Comment;

use App\Models\Post;
use App\Repositories\RepositoryInterface;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function getCommentsByPost(Post $post, $perPage);
}
