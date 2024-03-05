<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

}