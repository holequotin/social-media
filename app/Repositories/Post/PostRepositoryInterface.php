<?php
namespace App\Repositories\Post;

use App\Models\Group;
use App\Models\Post;
use App\Repositories\RepositoryInterface;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function getPosts($perPage);
    public function getPostsByUser($user, $perPage);
    public function getSharedLevel(Post $post);

    public function getPostsInGroup(Group $group, $perPage);
}
