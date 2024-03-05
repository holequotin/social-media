<?php
namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {
        
    }
    public function verifyUser($user)
    {
        $user = $this->userRepository->update($user->id,[
            'email_verified_at' => Carbon::now()
        ]);
        JWTAuth::invalidate(true);
        $token = auth()->login($user);
        return $token;
    }
}