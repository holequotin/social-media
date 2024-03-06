<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {
        
    }
    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id,$data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->update($id,$data);
    }

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->getUserByEmail($email);
    }
    
    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }
}
