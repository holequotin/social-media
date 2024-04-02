<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\Group;
use App\Repositories\Group\GroupRepositoryInterface;
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
        $validated['owner_id'] = auth()->id();
        $validated = ImageHelper::addPath($validated, 'groups/' . auth()->id(), 'url');

        return $this->groupRepository->create($validated)->load(['owner']);
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
}
