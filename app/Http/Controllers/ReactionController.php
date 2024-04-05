<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reaction\StoreReactionRequest;
use App\Http\Requests\Reaction\UpdateReactionRequest;
use App\Http\Resources\ReactionResource;
use App\Models\Post;
use App\Models\Reaction;
use App\Services\ReactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReactionController extends BaseApiController
{
    public function __construct(protected ReactionService $reactionService)
    {
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
    public function store(StoreReactionRequest $request)
    {
        $validated = $request->validated();
        $reaction = $this->reactionService->createReaction($validated);
        return $this->sendResponse([
            'message' => __('common.create.success', ['model' => 'reaction']),
            'reaction' => ReactionResource::make($reaction)
        ],);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reaction $reaction)
    {
        return $this->sendResponse([
            'reaction' => ReactionResource::make($reaction)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction)
    {
        $this->authorize('update', $reaction);
        $validated = $request->validated();
        $reaction = $this->reactionService->updateReaction($reaction->id, $validated);
        return $this->sendResponse([
            'message' => __('common.update.success', ['model' => 'reaction']),
            'reaction' => ReactionResource::make($reaction)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reaction $reaction)
    {
        $this->authorize('delete', $reaction);
        try {
            $this->reactionService->deleteReaction($reaction->id);
            return $this->sendResponse([
                'message' => __('common.delete.success', ['model' => 'reaction'])
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError(['error' => $th->getMessage()]);
        }
    }

    public function getReactionsByPost(Request $request, Post $post)
    {
        $reactions = $this->reactionService->getReactionsByPost($post->id, $request->type, $request->perPage);

        return $this->sendPaginateResponse(ReactionResource::collection($reactions));
    }
}
