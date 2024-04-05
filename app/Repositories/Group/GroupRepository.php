<?php

namespace App\Repositories\Group;

use App\Enums\GroupType;
use App\Enums\JoinGroupStatus;
use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepository;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function getModel()
    {
        return Group::class;
    }

    public function joinGroup(Group $group, User $user)
    {
        if (!$user->groups->contains($group)) {
            $user->groups()->attach($group->id);
        }
    }

    public function leaveGroup(Group $group, User $user)
    {
        if ($user->groups->contains($group)) {
            $user->groups()->detach($group->id);
        }
    }

    public function requestToJoinGroup(Group $group, User $user)
    {
        if ($group->type == GroupType::PRIVATE && !$user->groups->contains($group)) {
            $user->groups()->attach($group->id, [
                'status' => JoinGroupStatus::WAITING
            ]);
        }
    }

    public function acceptUser(Group $group, User $user)
    {
        if ($user->isWaitingAcceptGroup($group)) {
            $user->groups()->updateExistingPivot($group->id, ['status' => JoinGroupStatus::JOINED]);
        }
    }
}
