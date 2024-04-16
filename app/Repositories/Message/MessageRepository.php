<?php

namespace App\Repositories\Message;

use App\Models\Message;
use App\Models\User;
use App\Repositories\BaseRepository;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{

    public function getModel()
    {
        return Message::class;
    }

    public function getMessagesBetweenUsers(User $user1, User $user2, $perPage)
    {
        return $this->getModel()::where([
            ['from_user_id', $user1->id],
            ['to_user_id', $user2->id]
        ])->orWhere([
            ['from_user_id', $user2->id],
            ['to_user_id', $user1->id]
        ])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getLastMessages(User $user, $perPage)
    {
        $userId = $user->id;
        $lastMessages = Message::select('id', 'body', 'from_user_id', 'to_user_id', 'created_at', 'updated_at')
            ->whereIn('id', function ($query) use ($userId) {
                $query->selectRaw('MAX(id)')
                    ->from('messages')
                    ->where('from_user_id', $userId)
                    ->orWhere('to_user_id', $userId)
                    ->groupByRaw('CASE WHEN from_user_id = ' . $userId . ' THEN to_user_id ELSE from_user_id END');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $lastMessages;
    }
}
