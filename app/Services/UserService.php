<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected FileService $fileService
    )
    {
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->getUserByEmail($email);
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function uploadAvatar(User $user, array $validated)
    {
        $this->deleteAvatar($user);
        $validated = ImageHelper::addPath($validated, 'avatars/'.$user->id,'avatar');
        return $this->userRepository->update($user->id, $validated);
    }

    public function deleteAvatar(User $user)
    {
        if($user->avatar) {
            $paths = collect([$user->avatar]);
            $this->fileService->deleteImage($paths->all());
        }
    }

    public function searchUserByName($name, $perPage = 15)
    {
        return $this->userRepository->getUserByName($name, $perPage);
    }
}
