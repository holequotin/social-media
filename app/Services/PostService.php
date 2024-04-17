<?php

namespace App\Services;

use App\Enums\GroupType;
use App\Enums\PostType;
use App\Models\Group;
use App\Models\Post;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Post\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected PostImageService $postImageService,
        protected FileService              $fileService,
        protected GroupRepositoryInterface $groupRepository
    ) {
    }

    public function createPost($data)
    {
        $data['user_id'] = auth()->id();
        $data = $this->setTypeForPost($data);
        return $this->postRepository->create($data);
    }

    public function getPostById($postId)
    {
        return $this->postRepository->find($postId);
    }

    public function updatePost($post, $validated)
    {
        if (!$post->shared_post_id) $validated['images'] = [];
        try {
            DB::beginTransaction();
            $this->postImageService->createPostImages($validated['images'], $post->id);
            $this->postImageService->deletePostImagesById($validated['delete_image_id']);
            $urls = $this->fileService->storeImage('posts/' . $post->id, $validated['images']);
            DB::commit();
            return $this->postRepository->update($post->id, $validated);
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getPosts($perPage = 15)
    {
        return $this->postRepository->getPosts($perPage);
    }

    public function getPostsByUser($user, $perPage = 15)
    {
        return $this->postRepository->getPostsByUser($user, $perPage);
    }

    public function getPostsInGroup(Group $group, $perPage = 15)
    {
        return $this->postRepository->getPostsInGroup($group, $perPage);
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

    public function sharePost($validated)
    {
        $validated['user_id'] = auth()->id();
        $post = $this->postRepository->create($validated);
        $post->load(['user', 'sharedPost']);

        return $post;
    }

    public function setTypeForPost($data)
    {
        if (isset($data['group_id'])) {
            $group = $this->groupRepository->find($data['group_id']);
            switch ($group->type) {
                case GroupType::PRIVATE:
                    $data['type'] = PostType::PRIVATE;
                    break;
                case GroupType::PUBLIC:
                    $data['type'] = PostType::PUBLIC;
                    break;
                default:
                    $data['type'] = PostType::PUBLIC;
            }
        }

        return $data;
    }
}
