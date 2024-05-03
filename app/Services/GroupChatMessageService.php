<?php

namespace App\Services;

use App\Repositories\GroupChatMessage\GroupChatMessageRepositoryInterface;

class GroupChatMessageService
{
    public function __construct(protected GroupChatMessageRepositoryInterface $groupChatMessageRepository)
    {
    }

    public function storeMessage($validated)
    {
        $message = $this->groupChatMessageRepository->create($validated);
        $message->load(['user', 'groupChat']);

        return $message;
    }
}
