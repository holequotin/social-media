<?php
namespace App\Services;

use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerifyService
{
    public function sendVerifyEmail($user)
    {
        Notification::send($user,new EmailVerificationNotification($this->generateVerificationLink($user)));
    }

    public function generateVerificationLink($user)
    {
        $token = JWTAuth::fromUser($user);
        return config('app.url').'/api/auth/verify'.'?token='.$token;
    }
}