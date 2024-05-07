<?php

namespace App\Services;

use App\Enums\JoinGroupStatus;
use App\Enums\ReplyType;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use App\Repositories\GroupInvitation\GroupInvitationRepositoryInterface;
use App\Repositories\GroupUser\GroupUserRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;

class GroupInvitationService
{
    public function __construct(
        protected GroupInvitationRepositoryInterface $groupInvitationRepository,
        protected GroupUserRepositoryInterface       $groupUserRepository,
        protected UserRepositoryInterface            $userRepository
    )
    {
    }

    public function storeGroupInvitation($validated)
    {
        $validated['inviter_id'] = auth()->id();
        $invitation = $this->groupInvitationRepository->create($validated);
        $invitation->load(['group', 'inviter', 'beInvite']);

        return $invitation;
    }

    public function replyGroupInvitation(GroupInvitation $groupInvitation, $type)
    {
        if ((int)$type === ReplyType::ACCEPT) {
            $groupUser = $this->groupUserRepository->getModel()::where('user_id', $groupInvitation->be_invite_id)->where('group_id', $groupInvitation->group_id)->first();
            if ($groupUser) {
                $groupUser->status = JoinGroupStatus::JOINED;
                $groupUser->join_at = Carbon::now();
                $groupUser->save();
            } else {
                $this->groupUserRepository->create([
                    'group_id' => $groupInvitation->group_id,
                    'user_id' => $groupInvitation->be_invite_id,
                    'status' => JoinGroupStatus::JOINED,
                    'joined_at' => Carbon::now()
                ]);
            }
        }

        $groupInvitation->delete();
    }

    public function getUsersCanInvite(Group $group)
    {
        return $this->userRepository->getUsersCanInvite($group);
    }

    public function getGroupInvitations(User $user)
    {
        return $this->groupInvitationRepository->getInvitationsByUser($user->id);
    }
}
