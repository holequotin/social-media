<?php

namespace App\Repositories\Post;

use App\Enums\PostType;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
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

    public function find($id)
    {
        return $this->getModel()::with(['sharedPost'])->findOrFail($id);
    }

    public function paginate($perPage = 10)
    {
        return $this->getModel()::paginate($perPage);
    }

    public function getPosts($perPage)
    {
        $friends = $this->friendshipRepository->getFriendsByUser(auth()->user())->pluck('id')->all();
        $posts = Post::where('type', PostType::PUBLIC)
            ->orWhere(function ($query) use ($friends) {
                $query->where('type', PostType::FRIENDS)
                    ->whereIn('user_id', [...$friends, auth()->id()]);
            })->orderBy('created_at', 'desc')
            ->with(['reactions', 'sharedPost'])
            ->paginate($perPage);
        return $posts;
    }

    public function getPostsByUser($user, $perPage)
    {
        if ($user->is(auth()->user())) {
            return $this->getModel()::where('user_id', $user->id)->whereNull('group_id')->orderBy('created_at', 'desc')->with(['reactions', 'sharedPost'])->paginate($perPage);
        }
        $query = $this->getModel()::where('user_id', $user->id)
            ->where('type', PostType::PUBLIC);
        if ($user->checkIsFriend(auth()->id()))
        {
            $query = $query->orWhere(function($query) use ($user) {
                return $query->where('user_id', $user->id)
                            ->where('type', PostType::FRIENDS);
            });
        }
        return $query->whereNull('group_id')->orderBy('created_at', 'desc')->with(['reactions', 'sharedPost'])->paginate($perPage);
    }

    public function getSharedLevel(Post $post)
    {
        if ($post->sharedPost == null) return 0;
        return $this->getSharedLevel($post->sharedPost) + 1;
    }

    public function getPostsInGroup(Group $group, $perPage)
    {
        return $group->posts()->with(['reactions', 'sharedPost'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAllPostGroup(User $user)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');
        $groupIds = $user->groups()->pluck('groups.id')->toArray();
        return $this->getModel()::whereIn('group_id', $groupIds)->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
