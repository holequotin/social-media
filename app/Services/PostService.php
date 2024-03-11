<?php
namespace App\Services;

use App\Repositories\Post\PostRepositoryInterface;

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository, 
        protected PostImageService $postImageService,
        protected FileService $fileService) {
    }

    public function createPost($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->postRepository->create($data);
    }

    public function getPostById($postId)
    {
        return $this->postRepository->find($postId);
    }

    public function updatePost($postId,$validated)
    {
        $urls = $this->fileService->storeImage('posts',$validated['images']);
        $this->postImageService->createPostImages($urls,$postId);
        $this->postImageService->deletePostImagesById($validated['delete_image_id']);
        
        return $this->postRepository->update($postId,$validated);
    }
}
