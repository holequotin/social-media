<?php

namespace App\Repositories\GroupInvitation;

use App\Models\GroupInvitation;
use App\Repositories\BaseRepository;

class GroupInvitationRepository extends BaseRepository implements GroupInvitationRepositoryInterface
{

    public function getModel()
    {
        return GroupInvitation::class;
    }

    public function deleteByBeInviteUser($userId, $groupId)
    {
        return $this->getModel()::where('be_invite_id', $userId)
            ->where('group_id', $groupId)
            ->delete();
    }

    public function deleteByInviter($userId, $groupId)
    {
        return $this->getModel()::where('inviter_id', $userId)
            ->where('group_id', $groupId)
            ->delete();
    }

    public function getInvitationsByUser($userId)
    {
        $perPage = request()?->perPage ?? config('define.paginate.perPage');
        return $this->getModel()::where('be_invite_id', $userId)
            ->paginate($perPage);
    }
}
