<?php

namespace App\Repositories\GroupUser;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Repositories\BaseRepository;

class GroupUserRepository extends BaseRepository implements GroupUserRepositoryInterface
{

    public function getModel()
    {
        return GroupUser::class;
    }

    public function getRequestToJoinGroup(User $user, Group|null $group, $perPage)
    {
        if ($group) {
            $query = $this->getModel()::waiting()->where('group_id', $group->id)->where('user_id', $user->id);
        } else {
            $query = $this->getModel()::waiting()->where('user_id', $user->id);
        }

        return $query->with(['user', 'group'])->paginate($perPage);
    }
}
