<?php
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUserByEmail($email)
    {
        return User::where('email',$email)->first();
    }

    public function getUserByName($name, $perPage)
    {
        return User::where('name', 'like', '%' . $name . '%')->where('id', '!=', auth()->id())->paginate($perPage);
    }
}
