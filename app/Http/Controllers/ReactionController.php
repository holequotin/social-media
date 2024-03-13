<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reaction\StoreReactionRequest;
use App\Http\Requests\Reaction\UpdateReactionRequest;
use App\Http\Resources\ReactionResource;
use App\Models\Reaction;
use App\Services\ReactionService;
use Illuminate\Http\Response;

class ReactionController extends BaseApiController
{   
    public function __construct(protected ReactionService $reactionService) {
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
            'message' => __('reaction.create.success'),
            'reaction' => ReactionResource::make($reaction)
        ], Response::HTTP_OK);
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
    public function update(UpdateReactionRequest $request, Reaction $reaction)
    {
        $this->authorize('update',$reaction);
        $validated = $request->validated();
        $reaction = $this->reactionService->updateReaction($reaction->id,$validated);
        return $this->sendResponse([
            'message' => __('reaction.update.success'),
            'reaction' => ReactionResource::make($reaction)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
