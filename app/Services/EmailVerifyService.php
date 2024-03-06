<?php
namespace App\Services;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerifyService
{
    /**
     * Send verify email
     * @param App\Models\User $user
     * 
     * @return void
     */
    public function sendVerifyEmail($user)
    {
        Notification::send($user,new EmailVerificationNotification($this->generateVerificationLink($user)));
    }
    /**
     * Send reset password email
     * @param App\Models\User $user
     * 
     * @return void
     */
    public function sendResetPasswordEmail($user)
    {
        $token = JWTAuth::fromUser($user);
        Notification::send($user,new ResetPasswordNotification($token));
    }

    /**
     * Generate verification link
     * 
     * @param App\Models\User $user
     * 
     * @return string
     */
    public function generateVerificationLink($user)
    {
        $token = JWTAuth::fromUser($user);
        return config('app.url').'/api/auth/verify'.'?token='.$token;
    }
}
