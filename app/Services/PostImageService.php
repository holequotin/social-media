<?php
namespace App\Services;

use App\Helpers\ImageHelper;
use App\Repositories\PostImage\PostImageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PostImageService
{
    public function __construct(
        protected PostImageRepositoryInterface $postImageRepository, 
        protected FileService $fileService) {
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
        try {
            DB::beginTransaction();
            $urls = $this->postImageRepository->getUrlsById($postImageId);
            $result = $this->postImageRepository->destroy($postImageId);
            $paths = ImageHelper::urlsToPaths($urls);
            $this->fileService->deleteImage($paths->all());
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deletePostImagesByPost($postId)
    {
        $postImageId = $this->postImageRepository->getIdByPost($postId);  
        return $this->deletePostImagesById($postImageId->all());
    }
}
