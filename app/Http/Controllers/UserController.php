<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends BaseApiController
{
    public function show(User $user)
    {
        return $this->sendResponse(UserResource::make($user));
    }
}
