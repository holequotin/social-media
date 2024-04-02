<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\GroupService;
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
        $validated = $request->validated();
        $group = $this->groupService->createGroup($validated);

        return $this->sendResponse(GroupResource::make($group), Response::HTTP_CREATED);
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
}
