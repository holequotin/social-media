<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupChatMessage\StoreGroupChatMessageRequest;
use App\Http\Resources\GroupChatMessageResource;
use App\Models\GroupChat;
use App\Models\GroupChatMessage;
use App\Services\GroupChatMessageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupChatMessageController extends BaseApiController
{
    public function __construct(protected GroupChatMessageService $groupChatMessageService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GroupChat $groupChat)
    {
        $this->authorize('getMessages', $groupChat);
        $groupChatMessages = $this->groupChatMessageService->getMessagesByGroupChat($groupChat);

        return $this->sendPaginateResponse(GroupChatMessageResource::collection($groupChatMessages));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupChatMessageRequest $request)
    {
        $validated = $request->validated();
        $validated['from_user_id'] = auth()->id();
        $message = $this->groupChatMessageService->storeMessage($validated);

        return $this->sendResponse(GroupChatMessageResource::make($message), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupChatMessage $groupChatMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupChatMessage $groupChatMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupChatMessage $groupChatMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupChatMessage $groupChatMessage)
    {
        //
    }
}
