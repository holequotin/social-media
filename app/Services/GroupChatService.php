<?php

namespace App\Services;

use App\Enums\GroupChatRole;
use App\Repositories\GroupChat\GroupChatRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class GroupChatService
{
    public function __construct(
        protected GroupChatUserService         $groupChatUserService,
        protected GroupChatRepositoryInterface $groupChatRepository,
    )
    {
    }

    public function createGroupChat($validated)
    {
        try {
            DB::beginTransaction();
            $groupChat = $this->groupChatRepository->create([
                'name' => $validated['name']
            ]);
            $validated['group_chat_id'] = $groupChat->id;
            $this->groupChatUserService->addUsersToGroupChat(['users' => [auth()->id()], 'group_chat_id' => $groupChat->id], GroupChatRole::ADMIN);
            $this->groupChatUserService->addUsersToGroupChat($validated, GroupChatRole::MEMBER);
            DB::commit();

            return $groupChat;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateGroupChat($id, $validated)
    {
        return $this->groupChatRepository->update($id, $validated);
    }
}
