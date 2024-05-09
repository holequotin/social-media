<?php

namespace App\Repositories\GroupInvitation;

use App\Repositories\RepositoryInterface;

interface GroupInvitationRepositoryInterface extends RepositoryInterface
{
    public function deleteByBeInviteUser($userId, $groupId);
    public function deleteByInviter($userId, $groupId);

    public function getInvitationsByUser($userId);
}
