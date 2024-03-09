<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Services\FileService;
use App\Services\PostImageService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PostController extends BaseApiController  
{
    public function __construct(
        protected PostService $postService,
        protected FileService $fileService,
        protected PostImageService $postImageService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        try {
            $post = $this->postService->createPost($validated);
            $urls = $this->fileService->storeImage('posts',$validated['images']);
            $this->postImageService->createPostImages($urls,$post->id);
            $post = $this->postService->getPostById($post->id);
            
            return $this->sendResponse([
                "message" => __('post.create.success'),
                "post" => PostResource::make($post)
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th);

            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
