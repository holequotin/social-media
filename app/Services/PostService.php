<?php
namespace App\Services;

use App\Repositories\Post\PostRepositoryInterface;

class PostService
{
    public function __construct(protected PostRepositoryInterface $postRepository) {
    }

    public function createPost($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->postRepository->create($data);
    }
}
