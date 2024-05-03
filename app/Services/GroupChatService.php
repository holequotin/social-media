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
            $this->groupChatUserService->addUsersToGroupChat([auth()->id()], $groupChat->id, GroupChatRole::ADMIN);
            if (isset($validated['users'])) {
                $this->groupChatUserService->addUsersToGroupChat($validated['users'], $groupChat->id, GroupChatRole::MEMBER);
            }
            DB::commit();

            return $groupChat;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
