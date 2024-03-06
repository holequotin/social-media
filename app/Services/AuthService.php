<?php
namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {
        
    }
    /**
     * Verify user email
     * 
     * @param App\Models\User $user
     * 
     * @return string $token
     */
    public function verifyUser($user)
    {
        $user = $this->userRepository->update($user->id,[
            'email_verified_at' => Carbon::now()
        ]);
        JWTAuth::invalidate(true);
        $token = auth()->login($user);
        return $token;
    }

    public function checkValidToken($token)
    {
        $old_token = JWTAuth::getToken();
        JWTAuth::setToken($token);
        $check = JWTAuth::check();
        JWTAuth::setToken($old_token);
        return $check;
    }

    public function generateRefreshToken($user)
    {
        $refresh_ttl = (int)config('jwt.refresh_ttl');
        $refresh_token = JWTAuth::customClaims(['exp' => Carbon::now()->addMinutes($refresh_ttl)->timestamp])
        ->fromUser($user);
        return $refresh_token;
    }

    public function refreshToken($refresh_token)
    {
        $user = auth()->user();
        if(!is_null($user)){
            $token = JWTAuth::refresh();
            JWTAuth::invalidate();
            return $token;
        }
        $decoded = JWTAuth::getJWTProvider()->decode($refresh_token);
        $user = $this->userRepository->find($decoded['sub']);
        return JWTAuth::fromUser($user);
    }
}