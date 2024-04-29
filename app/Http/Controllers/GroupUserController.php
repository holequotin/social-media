<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupUser\UpdateShowPostTypeRequest;
use App\Http\Resources\GroupUserResource;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupUserController extends BaseApiController
{
    public function __construct(protected GroupUserService $groupUserService)
    {
    }

    public function getRequestsToJoinGroup(Request $request)
    {
        try {
            $joinRequests = $this->groupUserService->getRequestsToJoinGroup($request->groupId, $request->perPage);
            return $this->sendPaginateResponse(GroupUserResource::collection($joinRequests));
        } catch (Exception $exception) {
            Log::error($exception);
            return $this->sendError(['error' => $exception->getMessage()]);
        }
    }

    public function setShowPostType(UpdateShowPostTypeRequest $request, Group $group, User $user)
    {
        $validated = $request->validated();
        $groupUser = $this->groupUserService->setShowPostType($user, $group, $validated['type']);

        return $this->sendResponse(new GroupUserResource($groupUser));
    }
}
