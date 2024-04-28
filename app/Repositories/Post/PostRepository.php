<?php

namespace App\Repositories\Post;

use App\Enums\PostType;
use App\Enums\ShowPostType;
use App\Models\Group;
use App\Models\GroupUser;
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
            })->orderBy('posts.created_at', 'desc')
            ->with(['reactions', 'sharedPost'])
            ->paginate($perPage);
        return $posts;
    }

    public function getPostsByUser($user, $perPage)
    {
        if ($user->is(auth()->user())) {
            return $this->getModel()::where('user_id', $user->id)->whereNull('group_id')->orderBy('posts.created_at', 'desc')->with(['reactions', 'sharedPost'])->paginate($perPage);
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
        return $query->whereNull('group_id')->orderBy('posts.created_at', 'desc')->with(['reactions', 'sharedPost'])->paginate($perPage);
    }

    public function getSharedLevel(Post $post)
    {
        if ($post->sharedPost == null) return 0;
        return $this->getSharedLevel($post->sharedPost) + 1;
    }

    public function getPostsInGroup(Group $group, $perPage)
    {
        $groupUser = GroupUser::where('user_id', auth()->id())->where('group_id', $group->id)->first();
        $query = $group->posts()->with(['reactions', 'sharedPost'])->orderBy('posts.created_at', 'desc');

        if ($groupUser->show_post_type == ShowPostType::ALL) {
            return $query->paginate();
        }
        return $query->where('created_at', '>', $groupUser->joined_at)->paginate();
    }

    public function getAllPostGroup(User $user)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');

        $postIds = $user->groups()
            ->join('posts', 'groups.id', '=', 'posts.group_id')
            ->select('posts.*')
            ->whereRaw('case
            when group_user.show_post_type = "0"
            then posts.created_at > group_user.joined_at
            else 1 = 1
            end')
            ->orderBy('posts.created_at', 'desc')
            ->pluck('posts.id');

        return $this->getModel()::whereIn('id', $postIds)->orderBy('posts.created_at', 'desc')->paginate($perPage);
    }
}
