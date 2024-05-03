<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupChat\StoreGroupChatRequest;
use App\Http\Requests\GroupChat\UpdateGroupChatRequest;
use App\Http\Resources\GroupChatResource;
use App\Models\GroupChat;
use App\Services\GroupChatService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class GroupChatController extends BaseApiController
{
    public function __construct(protected GroupChatService $groupChatService)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupChatRequest $request)
    {
        try {
            $groupChat = $this->groupChatService->createGroupChat($request->validated());

            return $this->sendResponse(GroupChatResource::make($groupChat), Response::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupChat $groupChat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupChat $groupChat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupChatRequest $request, GroupChat $groupChat)
    {
        $groupChat = $this->groupChatService->updateGroupChat($groupChat->id, $request->validated());

        return $this->sendResponse(GroupChatResource::make($groupChat));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupChat $groupChat)
    {
        $this->authorize('delete', $groupChat);
        $groupChat->delete();

        return $this->sendResponse(['message' => __('common.group_chat.delete_success')],);
    }
}
