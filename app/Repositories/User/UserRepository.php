<?php
namespace App\Repositories\User;

use App\Enums\JoinGroupStatus;
use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUserByEmail($email)
    {
        return User::where('email',$email)->first();
    }

    public function getUserByName($name, $perPage)
    {
        return User::where('name', 'like', '%' . $name . '%')->where('id', '!=', auth()->id())->paginate($perPage);
    }

    public function getUsersInGroup(Group $group, $perPage)
    {
        return $group->members()->paginate($perPage);
    }

    public function isInGroup(Group $group, User $user)
    {
        return $user->groups()->wherePivot('group_id', $group->id)
            ->wherePivot('status', JoinGroupStatus::JOINED)
            ->exists();
    }

    public function isWaitingAcceptGroup(Group $group, User $user)
    {
        return $user->groups()->wherePivot('group_id', $group->id)
            ->wherePivot('status', JoinGroupStatus::WAITING)
            ->exists();
    }

    public function getGroupsByUser(User $user, $perPage)
    {
        return $user->groups()->paginate($perPage);
    }
}
