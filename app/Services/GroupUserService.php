<?php

namespace App\Services;

use App\Models\Group;
use App\Models\User;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\GroupUser\GroupUserRepositoryInterface;
use Exception;

class GroupUserService
{
    public function __construct(
        protected GroupUserRepositoryInterface $groupUserRepository,
        protected GroupRepositoryInterface     $groupRepository
    )
    {
    }

    public function getRequestsToJoinGroup($groupId, $perPage = 10)
    {
        try {
            if ($groupId) {
                $group = $this->groupRepository->find($groupId);
                if (!auth()->user()->is($group->owner)) throw new Exception(__('exception.group.is_not_owner'));
                return $this->groupUserRepository->getRequestToJoinGroup(auth()->user(), $group, $perPage);
            }
            return $this->groupUserRepository->getRequestToJoinGroup(auth()->user(), null, $perPage);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function setShowPostType(User $user, Group $group, $type)
    {
        return $this->groupUserRepository->setShowPostType($user, $group, $type);
    }

    public function setGroupRole($groupId, $validated)
    {
        return $this->groupUserRepository->getModel()::where('group_id', $groupId)
            ->where('user_id', $validated['user_id'])
            ->update(['role' => $validated['role']]);
    }

    public function getMembers(Group $group)
    {
        return $this->groupUserRepository->getMembers($group);
    }
}
