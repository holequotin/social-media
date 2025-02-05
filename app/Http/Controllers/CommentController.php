<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CommentController extends BaseApiController
{
    public function __construct(
        protected CommentService $commentService,
        protected FileService $fileService
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
    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();
        try {
            $comment = $this->commentService->createComment($validated);
            return $this->sendResponse([
                'message' => __('common.create.success', ['model' => 'comment']),
                'comment' => CommentResource::make($comment)
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();
        try {
            $comment = $this->commentService->updateComment($comment,$validated);
            return $this->sendResponse([
                "message" => __('common.update.success', ['model' => 'comment']),
                "comment" => CommentResource::make($comment)
            ], Response::HTTP_OK);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        try {
            $this->commentService->deleteComment($comment);

            return $this->sendResponse([
                'message' => __('common.delete.success', ['model' => 'comment'])
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError($th->getMessage());
        }
    }

    public function getCommentsByPost(Request $request, Post $post)
    {
        $comments = $this->commentService->getCommentsByPost($post, $request->perPage);
        return $this->sendPaginateResponse(CommentResource::collection($comments));
    }
}
