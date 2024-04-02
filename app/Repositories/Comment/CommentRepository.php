<?php
namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\BaseRepository;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function getModel()
    {
        return Comment::class;
    }

    public function getCommentsByPost(Post $post, $perPage)
    {
        return $this->getModel()::where('post_id', $post->id)->orderBy('created_at', 'desc')->with('user')->paginate($perPage);
    }
}
