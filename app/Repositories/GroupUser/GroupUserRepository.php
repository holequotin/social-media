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
            $queryA = $this->getModel()::waiting()->where('group_id', $group->id);
        } else {
            $queryA = $this->getModel()::waiting()->whereIn('user_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('groups')
                    ->where('owner_id', $user->id);
            });
        }

        return $queryA->with(['user', 'group'])->paginate($perPage);
    }
}
