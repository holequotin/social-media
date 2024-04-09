<?php

namespace App\Services;

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
}
