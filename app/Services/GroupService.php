<?php

namespace App\Services;

use App\Enums\GroupType;
use App\Helpers\ImageHelper;
use App\Models\Group;
use App\Models\User;
use App\Notifications\GroupRequestNotification;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class GroupService
{
    public function __construct(
        protected GroupRepositoryInterface $groupRepository,
        protected FileService             $fileService,
        protected UserRepositoryInterface $userRepository,
    )
    {
    }

    public function createGroup($validated)
    {
        try {
            DB::beginTransaction();
            $validated['owner_id'] = auth()->id();
            $validated['slug'] = '';
            $validated = ImageHelper::addPath($validated, 'groups/' . auth()->id(), 'url');
            $group = $this->groupRepository->create($validated)->load(['owner']);
            $group->slug = $this->createGroupSlug($group->id, $group->name);
            $group->save();
            auth()->user()->groups()->attach($group->id, ['joined_at' => Carbon::now()]);
            $group->load('owner');
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
        if (isset($validated['name']) && $validated['name'] != $group->name) {
            $validated['slug'] = $this->createGroupSlug($group->id, $validated['name']);
        }
        return $this->groupRepository->update($group->id, $validated)->load(['owner']);
    }

    public function deleteGroup(Group $group): void
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

    public function deleteImageGroup(Group $group): void
    {
        $paths = collect([$group->url]);
        if ($group->url) {
            $this->fileService->deleteImage($paths->all());
        }
    }

    public function joinGroup(Group $group, User $user): void
    {
        if ($group->type == GroupType::PRIVATE) {
            throw new Exception(__('exception.group.join_not_allowed'));
        }
        if ($user->groups->contains($group)) {
            throw new Exception(__('exception.group.has_joined'));
        }
        $this->groupRepository->joinGroup($group, $user);
    }

    public function leaveGroup(Group $group, User $user): void
    {
        if (!$user->groups->contains($group)) {
            throw new Exception(__('exception.group.has_not_joined'));
        }
        if ($user->is($group->owner)) {
            throw new Exception(__('exception.group.is_owner'));
        }
        $this->groupRepository->leaveGroup($group, $user);
    }

    public function requestToJoinGroup(Group $group, User $user): void
    {
        if (!$group->type == GroupType::PRIVATE) {
            throw new Exception(__('exception.group.invalid'));
        }
        $this->groupRepository->requestToJoinGroup($group, $user);
        $group->owner->notify(new GroupRequestNotification($group, $user));
    }

    public function acceptUser(Group $group, User $user)
    {
        $this->groupRepository->acceptUser($group, $user);
    }

    public function getGroupsByUser(User $user, $perPage)
    {
        return $this->userRepository->getGroupsByUser($user, $perPage);
    }

    public function searchGroupByName($name, $perPage = 15)
    {
        return $this->groupRepository->getGroupsByName($name, $perPage);
    }

    public function getJoinGroupStatus(Group $group, User $user)
    {
        return $this->groupRepository->getJoinGroupStatus($group, $user);
    }

    public function getGroupBySlug($slug)
    {
        return $this->groupRepository->getGroupBySlug($slug);
    }

    public function createGroupSlug($groupId, $name)
    {
        $slug = Str::slug($name);
        if ($this->groupRepository->getModel()::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . $groupId;
        }
        return $slug;
    }
}
