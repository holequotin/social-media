<?php
namespace App\Repositories\User;

use App\Models\Group;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getUserByEmail($email);
    public function getUserByName($name, $perPage);

    public function getUsersInGroup(Group $group, $perPage);
}
