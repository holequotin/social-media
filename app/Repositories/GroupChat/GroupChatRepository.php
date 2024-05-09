<?php

namespace App\Repositories\GroupChat;

use App\Enums\FriendshipStatus;
use App\Models\GroupChat;
use App\Repositories\BaseRepository;

class GroupChatRepository extends BaseRepository implements GroupChatRepositoryInterface
{

    public function getModel()
    {
        return GroupChat::class;
    }

    public function getGroupChatsByUser($userId)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');
        return $this->getModel()::join('group_chat_users', 'group_chat_users.group_chat_id', '=', 'group_chats.id')
            ->where('group_chat_users.user_id', '=', $userId)
            ->select('group_chats.*')
            ->paginate($perPage);
    }

    public function getUsersCanAdd($groupChatId)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');

        return auth()->user()->friends()->wherePivot('status', FriendshipStatus::ACCEPTED)->getQuery()->select('users.*')
            ->whereNotIn('users.id', function ($query) use ($groupChatId) {
                $query->select('user_id')
                    ->from('group_chat_users')
                    ->where('group_chat_id', $groupChatId);
            })
            ->union(auth()->user()->isFriends()->wherePivot('status', FriendshipStatus::ACCEPTED)->getQuery()->select('users.*')->whereNotIn('users.id', function ($query) use ($groupChatId) {
                $query->select('user_id')
                    ->from('group_chat_users')
                    ->where('group_chat_id', $groupChatId);
            }))
            ->paginate($perPage);
    }
}
