<?php

namespace App\Repositories\Group;

use App\Enums\GroupType;
use App\Enums\JoinGroupStatus;
use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        parent::__construct();
    }
    public function getModel()
    {
        return Group::class;
    }

    public function joinGroup(Group $group, User $user)
    {
        if (!$user->groups->contains($group)) {
            $user->groups()->attach($group->id, ['joined_at' => Carbon::now()]);
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
        if ($this->userRepository->isWaitingAcceptGroup($group, $user)) {
            $user->groups()->updateExistingPivot($group->id, ['status' => JoinGroupStatus::JOINED, 'joined_at' => Carbon::now()]);
        }
    }

    public function getGroupsByName($name, $perPage = 15)
    {
        return $this->getModel()::where('name', 'like', '%' . $name . '%')->paginate($perPage);
    }

    public function getJoinGroupStatus(Group $group, User $user)
    {
        return $group->members()->wherePivot('user_id', $user->id)->first()?->pivot->status;
    }
}
