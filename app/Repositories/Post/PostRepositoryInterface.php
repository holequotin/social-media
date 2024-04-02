<?php
namespace App\Repositories\Post;

use App\Repositories\RepositoryInterface;

interface PostRepositoryInterface extends RepositoryInterface
{
    public function getPosts($perPage);

    public function getPostsByUser($user, $perPage);
}
