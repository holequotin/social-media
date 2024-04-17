<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\SharePostRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use App\Services\FileService;
use App\Services\PostImageService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PostController extends BaseApiController
{
    public function __construct(
        protected PostService $postService,
        protected FileService $fileService,
        protected PostImageService $postImageService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $this->postService->getPosts($request->perPage);
        return $this->sendPaginateResponse(PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        try {
            $post = $this->postService->createPost($validated);
            $this->postImageService->createPostImages($validated['images'], $post->id);
            $urls = $this->fileService->storeImage('posts/' . $post->id, $validated['images']);
            $post = $this->postService->getPostById($post->id);

            return $this->sendResponse([
                "message" => __('common.create.success', ['model' => 'post']),
                "post" => PostResource::make($post)
            ], Response::HTTP_CREATED);
        } catch (Throwable $th) {
            Log::error($th);

            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('show', $post);
        $post->load(['user', 'sharedPost']);
        return $this->sendResponse(['post' => PostResource::make($post)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
        $this->authorize('update', $post);
        try {
            $validated = $request->validated();
            $this->postService->updatePost($post, $validated);
            $post = $this->postService->getPostById($post->id);
            return $this->sendResponse([
                "message" => __('common.update.success', ['model' => 'post']),
                "post" => PostResource::make($post)
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        try {
            $this->postService->deletePost($post);
            return $this->sendResponse([
                "message" => __('common.delete.success', ['model' => 'post']),
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    public function getPostsByUser(Request $request, User $user)
    {
        $posts = $this->postService->getPostsByUser($user, $request->perPage);
        return $this->sendPaginateResponse(PostResource::collection($posts));
    }

    public function getPostsInGroup(Request $request, Group $group)
    {
        $this->authorize('getPosts', $group);
        $posts = $this->postService->getPostsInGroup($group, $request->perPage);

        return $this->sendPaginateResponse(PostResource::collection($posts));
    }

    public function share(SharePostRequest $request)
    {
        $validated = $request->validated();
        $post = $this->postService->getPostById($validated['shared_post_id']);
        $this->authorize('share', $post);
        $newPost = $this->postService->sharePost($validated);

        return $this->sendResponse(PostResource::make($newPost));
    }

    public function getAllPostGroup(Request $request)
    {
        $posts = $this->postService->getAllPostGroup(auth()->user());

        return $this->sendPaginateResponse(PostResource::collection($posts));
    }
}
