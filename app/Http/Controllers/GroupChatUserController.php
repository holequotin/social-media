<?php

namespace App\Http\Controllers;

use App\Enums\GroupChatRole;
use App\Http\Requests\GroupChatUser\StoreGroupChatUserRequest;
use App\Http\Requests\GroupChatUser\UpdateGroupChatRoleRequest;
use App\Http\Resources\GroupChatUserResource;
use App\Models\GroupChat;
use App\Models\GroupChatUser;
use App\Services\GroupChatUserService;

class GroupChatUserController extends BaseApiController
{
    public function __construct(protected GroupChatUserService $groupChatUserService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GroupChat $groupChat)
    {
        $groupChatUsers = $this->groupChatUserService->getByGroupChat($groupChat);

        return $this->sendPaginateResponse(GroupChatUserResource::collection($groupChatUsers));
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
    public function store(StoreGroupChatUserRequest $request)
    {
        $validated = $request->validated();
        $this->groupChatUserService->addUsersToGroupChat($validated, GroupChatRole::MEMBER);

        return $this->sendResponse(['message' => __('common.group_chat.add_success')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupChatUser $groupChatUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupChatUser $groupChatUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRole(UpdateGroupChatRoleRequest $request, GroupChatUser $groupChatUser)
    {
        $this->authorize('update', $groupChatUser);
        $this->groupChatUserService->updateRole($groupChatUser, $request->validated());

        return $this->sendResponse(['message' => __('common.group_chat.update_role_success')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupChatUser $groupChatUser)
    {
        $this->authorize('delete', $groupChatUser);
        $groupChatUser->delete();

        return $this->sendResponse(['message' => __('common.group_chat.remove_success')]);
    }
}
