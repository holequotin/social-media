<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupInvitation\ReplyInvitationRequest;
use App\Http\Requests\GroupInvitation\StoreGroupInvitationRequest;
use App\Http\Resources\GroupInvitationResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Services\GroupInvitationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupInvitationController extends BaseApiController
{

    public function __construct(protected GroupInvitationService $groupInvitationService)
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
    public function store(StoreGroupInvitationRequest $request)
    {
        $invitation = $this->groupInvitationService->storeGroupInvitation($request->validated());

        return $this->sendResponse(GroupInvitationResource::make($invitation), Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function replyInvitation(ReplyInvitationRequest $request, GroupInvitation $groupInvitation)
    {
        $validated = $request->validated();
        $this->groupInvitationService->replyGroupInvitation($groupInvitation, $validated['type']);

        return $this->sendResponse();
    }

    public function getUsersCanInvite(Request $request, Group $group)
    {
        return $this->sendPaginateResponse(UserResource::collection($this->groupInvitationService->getUsersCanInvite($group)));
    }
}
