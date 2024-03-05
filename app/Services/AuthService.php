<?php
namespace App\Services;

use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function verifyUser($user)
    {
        //TODO: Move to repository
        $user->email_verified_at = Carbon::now();
        $user->save();
        JWTAuth::invalidate(true);
        $token = auth()->login($user);
        return $token;
    }
}