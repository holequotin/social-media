<?php

namespace App\Repositories\GroupChatMessage;

use App\Models\GroupChat;
use App\Models\GroupChatMessage;
use App\Repositories\BaseRepository;

class GroupChatMessageRepository extends BaseRepository implements GroupChatMessageRepositoryInterface
{

    public function getModel()
    {
        return GroupChatMessage::class;
    }

    public function getMessageByGroupChat(GroupChat $groupChat)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');
        return $this->getModel()::where('group_chat_id', $groupChat->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
