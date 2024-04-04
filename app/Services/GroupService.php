<?php

namespace App\Services;

use App\Enums\GroupType;
use App\Helpers\ImageHelper;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Group\GroupRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class GroupService
{
    public function __construct(
        protected GroupRepositoryInterface $groupRepository,
        protected FileService              $fileService
    )
    {
    }

    public function createGroup($validated)
    {
        try {
            DB::beginTransaction();
            $validated['owner_id'] = auth()->id();
            $validated = ImageHelper::addPath($validated, 'groups/' . auth()->id(), 'url');
            $group = $this->groupRepository->create($validated)->load(['owner']);
            auth()->user()->groups()->attach($group->id);
            DB::commit();
            return $group;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateGroup($validated, Group $group)
    {
        if (isset($validated['image'])) {
            $this->deleteImageGroup($group);
            $validated = ImageHelper::addPath($validated, 'groups/' . auth()->id(), 'url');
        }

        return $this->groupRepository->update($group->id, $validated)->load(['owner']);
    }

    public function deleteGroup(Group $group)
    {
        try {
            DB::beginTransaction();
            $this->deleteImageGroup($group);
            $this->groupRepository->delete($group->id);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function deleteImageGroup(Group $group)
    {
        $paths = collect([$group->url]);
        if ($group->url) {
            $this->fileService->deleteImage($paths->all());
        }
    }

    public function joinGroup(Group $group, User $user)
    {
        if ($group->type == GroupType::PRIVATE) {
            throw new Exception(__('exception.group.join_not_allowed'));
        }
        if ($user->groups->contains($group)) {
            throw new Exception(__('exception.group.has_joined'));
        }
        $this->groupRepository->joinGroup($group, $user);
    }

    public function leaveGroup(Group $group, User $user)
    {
        if (!$user->groups->contains($group)) {
            throw new Exception(__('exception.group.has_not_joined'));
        }
        $this->groupRepository->leaveGroup($group, $user);
    }
}
