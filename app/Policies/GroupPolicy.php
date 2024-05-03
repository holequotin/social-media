<?php

namespace App\Policies;

use App\Enums\JoinGroupStatus;
use App\Models\Group;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;

class GroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function update(User $user, Group $group)
    {
        return $user->id == $group->owner_id;
    }

    public function delete(User $user, Group $group)
    {
        return $user->id == $group->owner_id;
    }

    public function removeUser(User $user, Group $group, User $removedUser)
    {
        $isAdmin = $this->userRepository->isAdmin($group, $user);
        return ($user->is($group->owner) || $isAdmin) && $removedUser->groups->contains($group);
    }

    public function acceptUser(User $user, Group $group, User $acceptedUser)
    {
        $isAdmin = $this->userRepository->isAdmin($group, $user);
        $isOwner = $user->is($group->owner);

        return ($isAdmin || $isOwner) && $acceptedUser->groups()
                ->wherePivot('group_id', $group->id)
                ->wherePivot('status', JoinGroupStatus::WAITING)
                ->exists();

    }

    public function getPosts(User $user, Group $group)
    {
        return $this->userRepository->isInGroup($group, $user);
    }

    public function getUsers(User $user, Group $group)
    {
        return $this->userRepository->isInGroup($group, $user);
    }
}
