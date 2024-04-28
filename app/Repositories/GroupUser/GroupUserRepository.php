<?php

namespace App\Repositories\GroupUser;

use App\Enums\UserStatus;
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
            $query = $this->getModel()::waiting()->where('group_id', $group->id);
        } else {
            $query = $this->getModel()::waiting()->whereIn('group_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('groups')
                    ->where('owner_id', $user->id);
            });
        }

        return $query->whereNotIn('user_id', function ($query) {
            $query->select('id')->from('users')
                ->where('status', UserStatus::BLOCKED);
        })->with(['user', 'group'])->paginate($perPage);
    }

    public function setShowPostType(User $user, Group $group, $type)
    {
        $groupUser = $this->getModel()::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->first();

        $groupUser->show_post_type = $type;
        $groupUser->save();

        return $groupUser;
    }
}
