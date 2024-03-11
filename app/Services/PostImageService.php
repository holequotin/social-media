<?php
namespace App\Services;

use App\Repositories\PostImage\PostImageRepositoryInterface;

class PostImageService
{
    public function __construct(protected PostImageRepositoryInterface $postImageRepository, protected FileService $fileService) {
    }

    public function createPostImages($urls, $postId)
    {   
        $collection = collect($urls);
        $values = $collection->map(function ($value, $key) use ($postId) {
            return ['url' => $value,'post_id' => $postId];
        });
        return $this->postImageRepository->insert($values->all());
    }

    public function deletePostImagesById($postImageId = [])
    {
        $urls = $this->postImageRepository->getUrlsById($postImageId);

        $paths = $urls->map(function ($item, $key) {
            $path = parse_url($item,PHP_URL_PATH);
            return str_replace('storage','public',$path);
        });
        $this->fileService->deleteImage($paths->all());

        return $this->postImageRepository->destroy($postImageId);
    }
}
