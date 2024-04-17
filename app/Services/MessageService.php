<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Message\MessageRepositoryInterface;

class MessageService
{
    public function __construct(protected MessageRepositoryInterface $messageRepository)
    {
    }

    public function storeMessage($validated)
    {
        $validated['from_user_id'] = auth()->id();
        return $this->messageRepository->create($validated)->load(['fromUser', 'toUser']);
    }

    public function getMessageWithUser(User $user)
    {
        return $this->messageRepository->getMessagesBetweenUsers($user, auth()->user());
    }

    public function getLastMessages(User $user)
    {
        return $this->messageRepository->getLastMessages($user);
    }
}
