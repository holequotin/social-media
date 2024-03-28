<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected PostImageService $postImageService,
        protected FileService $fileService
    ) {
    }

    public function createPost($data)
    {
        $data['user_id'] = auth()->id();
        return $this->postRepository->create($data);
    }

    public function getPostById($postId)
    {
        return $this->postRepository->find($postId);
    }

    public function updatePost($postId, $validated)
    {
        try {
            DB::beginTransaction();
            $this->postImageService->createPostImages($validated['images'], $postId);
            $this->postImageService->deletePostImagesById($validated['delete_image_id']);
            $urls = $this->fileService->storeImage('posts/'.$postId, $validated['images']);
            DB::commit();
            return $this->postRepository->update($postId, $validated);
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getPosts()
    {
        return $this->postRepository->getPosts();
    }

    public function getPostsByUser($user)
    {
        return $this->postRepository->getPostsByUser($user);
    }

    public function deletePost(Post $post)
    {
        try {
            DB::beginTransaction();
            $this->postImageService->deletePostImagesByPost($post->id);
            $this->postRepository->delete($post->id);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
