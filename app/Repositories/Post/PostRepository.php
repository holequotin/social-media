<?php

namespace App\Repositories\Post;

use App\Enums\PostType;
use App\Models\Post;
use App\Repositories\BaseRepository;
use App\Repositories\Friendship\FriendshipRepositoryInterface;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{

    public function __construct(protected FriendshipRepositoryInterface $friendshipRepository)
    {
        parent::__construct();
    }

    public function getModel()
    {
        return Post::class;
    }

    public function find($postId)
    {
        return $this->getModel()::with('images')->findOrFail($postId);
    }

    public function paginate($perPage = 10)
    {
        return $this->getModel()::with('images')->paginate($perPage);
    }

    public function getPosts()
    {
        $friends = $this->friendshipRepository->getFriendsByUser(auth()->user())->pluck('id')->all();
        $posts = Post::where('type', PostType::PUBLIC)
            ->orWhere(function ($query) use ($friends) {
                $query->where('type', PostType::FRIENDS)
                    ->whereIn('user_id', $friends);
            })->orderBy('created_at', 'desc')->with(['user', 'images', 'reactions']);
        return $posts;
    }   

    public function getPostsByUser($user)
    {
        if ($user->is(auth()->user())) {
            return $this->getModel()::where('user_id', $user->id)->orderBy('created_at', 'desc')->with(['user', 'images', 'reactions']);
        }
        $query = $this->getModel()::where('user_id', $user->id)
            ->where('type', PostType::PUBLIC);
        if($user->checkIsFriend(auth()->user()))
        {
            $query = $query->orWhere(function($query) use ($user) {
                return $query->where('user_id', $user->id)
                            ->where('type', PostType::FRIENDS);
            });
        }
        return $query->orderBy('created_at', 'desc')->with(['user', 'images', 'reactions']);;
    }
}
