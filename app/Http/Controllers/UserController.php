<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\User\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    public function __construct(protected UserService $userService) {
    }

    public function show(User $user)
    {
        return $this->sendResponse(UserResource::make($user));
    }

    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->updateUser(auth()->id(), $validated);
        return $this->sendResponse(UserResource::make($user));
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->updateUser(auth()->id(), $validated);
        return $this->sendResponse(['message' => __('auth.reset_password')]);
    }

    public function uploadAvatar(UploadAvatarRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->uploadAvatar(auth()->user(), $validated);

        return $this->sendResponse(UserResource::make($user));
    }

    public function search(Request $request)
    {
        $name = $request->name;
        $perPage = $request->perPage;
        $users = $this->userService->searchUserByName($name)->paginate($perPage);

        return $this->sendPaginateResponse(UserResource::collection($users));
    }
}
