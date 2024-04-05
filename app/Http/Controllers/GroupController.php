<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class GroupController extends BaseApiController
{
    public function __construct(protected GroupService $groupService)
    {
    }

    public function store(StoreGroupRequest $request)
    {
        try {
            $validated = $request->validated();
            $group = $this->groupService->createGroup($validated);
            return $this->sendResponse(GroupResource::make($group), Response::HTTP_CREATED);
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->sendError(["error" => $th->getMessage()]);
        }
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $this->authorize('update', $group);
        $validated = $request->validated();
        $group = $this->groupService->updateGroup($validated, $group);

        return $this->sendResponse(GroupResource::make($group));
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);
        try {
            $this->groupService->deleteGroup($group);
            return $this->sendResponse([
                'message' => __('common.delete.success', ['model' => 'group'])
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return $this->sendError($th->getMessage());
        }
    }

    public function joinGroup(Request $request, Group $group)
    {
        try {
            $this->groupService->joinGroup($group, auth()->user());
            return $this->sendResponse(['message' => __('common.group.join_success')]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function leaveGroup(Request $request, Group $group)
    {
        try {
            $this->groupService->leaveGroup($group, auth()->user());
            return $this->sendResponse(['message' => __('common.group.leave_success')]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function requestToJoinGroup(Request $request, Group $group)
    {
        try {
            $this->groupService->requestToJoinGroup($group, auth()->user());

            return $this->sendResponse(['message' => __('common.group.request_success')]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function acceptToJoinGroup(Request $request, Group $group, User $user)
    {
        $this->authorize('acceptUser', [$group, $user]);
        $this->groupService->acceptUser($group, $user);

        return $this->sendResponse(['message' => __('common.group.accept_success')]);
    }

    public function removeUserFromGroup(Request $request, Group $group, User $user)
    {
        $this->authorize('removeUser', [$group, $user]);
        try {
            $this->groupService->leaveGroup($group, $user);
            return $this->sendResponse(['message' => __('common.group.remove_success')]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getGroupsByUser(Request $request, User $user)
    {
        $perPage = $request->perPage;
        $groups = $this->groupService->getGroupsByUser($user, $perPage);

        return $this->sendPaginateResponse(GroupResource::collection($groups));
    }
}
