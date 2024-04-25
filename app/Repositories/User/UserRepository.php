<?php
namespace App\Repositories\User;

use App\Enums\FriendshipStatus;
use App\Enums\JoinGroupStatus;
use App\Models\Friendship;
use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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
        return $group->members()->wherePivot('status', JoinGroupStatus::JOINED)->paginate($perPage);
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

    public function getMutualFriends(User $user1, User $user2)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');

        $mutualFriendIds = $this->getMutualFriendIds($user1, $user2);

        return $this->getModel()::whereIn('id', $mutualFriendIds)->paginate($perPage);
    }

    public function getSuggestionFriends(User $user)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');

        $friendIds = $user->friends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id')
            ->merge($user->isFriends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id'));

        $suggestion =
            DB::table(Friendship::selectRaw('from_user_id as user_id, count(from_user_id) as mutual_friend')
                ->whereIn('to_user_id', $friendIds)
                ->whereNotIn('from_user_id', $friendIds)
                ->where('status', FriendshipStatus::ACCEPTED)
                ->whereNot('from_user_id', $user->id)
                ->groupBy('user_id')->unionAll(
                    Friendship::selectRaw('to_user_id as user_id, count(to_user_id) as mutual_friend')
                        ->whereIn('from_user_id', $friendIds)
                        ->whereNotIn('to_user_id', $friendIds)
                        ->where('status', FriendshipStatus::ACCEPTED)
                        ->whereNot('to_user_id', $user->id)
                        ->groupBy('user_id')
                ))->join('users', 'users.id', '=', 'user_id')
                ->selectRaw('users.*,sum(mutual_friend) as mutual_friend')->groupBy('users.id')
                ->orderBy('mutual_friend', 'desc');
        return $suggestion->take(10)->get();
    }

    public function getMutualFriendIds(User $user1, User $user2)
    {
        $friendId1 = $user1->friends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id')
            ->merge($user1->isFriends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id'));

        $friendId2 = $user2->friends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id')
            ->merge($user2->isFriends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id'));

        return $friendId1->intersect($friendId2);
    }

    public function getRandomSuggestionFriends(User $user, $ids, $number)
    {
        $friendIds = $user->friends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id')
            ->merge($user->isFriends()->where('status', FriendshipStatus::ACCEPTED)->pluck('users.id'));

        return $this->getModel()::whereNotIn('id', [...$friendIds, $user->id, ...$ids])
            ->inRandomOrder()
            ->take($number)
            ->get();
    }
}
