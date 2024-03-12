<?php
namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function getModel()
    {
        return Post::class;
    }

    public function find($postId)
    {
        return $this->getModel()::with('images')->find($postId);
    }

    public function paginate($perPage = 10)
    {
        return $this->getModel()::with('images')->paginate($perPage);
    }
}
