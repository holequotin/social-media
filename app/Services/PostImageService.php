<?php
namespace App\Services;

use App\Repositories\PostImage\PostImageRepositoryInterface;

class PostImageService
{
    public function __construct(protected PostImageRepositoryInterface $postImageRepository) {

    }

    public function createPostImages($urls, $postId)
    {   
        $collection = collect($urls);
        $values = $collection->map(function ($value, $key) use ($postId) {
            return ['url' => $value,'post_id' => $postId];
        });
        return $this->postImageRepository->insert($values->all());
    }
}
