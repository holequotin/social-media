<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupUserResource;
use App\Services\GroupUserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            return $this->sendError(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
