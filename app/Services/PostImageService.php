<?php
namespace App\Services;

use App\Repositories\PostImage\PostImageRepositoryInterface;

class PostImageService
{
    public function __construct(protected PostImageRepositoryInterface $postImageRepository) {

    }

    public function createPostImages($urls, $postId)
    {
        $values = [];
        foreach ($urls as $url) {
            $values[] = ['url' => $url, 'post_id' => $postId];
        }
        return $this->postImageRepository->insert($values);
    }
}
