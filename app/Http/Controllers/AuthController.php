<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\EmailVerifyService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class AuthController extends BaseApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(
        protected EmailVerifyService $emailVerifyService,
        protected UserService $userService,
        protected AuthService $authService
    ) {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->createUser($validated);
        $this->emailVerifyService->sendVerifyEmail($user);

        return $this->sendResponse([
            "message" => __('mail.send.success', ['type' => 'Verify']),
            "user" => UserResource::make($user)
        ], Response::HTTP_CREATED);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth()->attempt($credentials)) {
            return $this->sendError(["error" => __("auth.unauthorized")], Response::HTTP_UNAUTHORIZED);
        }
        $user = auth()->user();
        if (!$user->email_verified_at) {
            auth()->logout();
            $this->emailVerifyService->sendVerifyEmail($user);
            return $this->sendResponse(["message" => __("mail.send.success", ["type" => "Verify"])]);
        }
        $refreshToken = $this->authService->generateRefreshToken($user);

        return $this->respondWithToken($token, $refreshToken);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->sendResponse(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();
        return $this->sendResponse(["message" => __("auth.logout")]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $validated = $request->validated();
        $token = $this->authService->refreshToken($validated["refresh_token"]);
        return $this->respondWithToken($token, $validated["refresh_token"]);
    }
    
    /**
     * Verify user email
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user) {
            $token = $this->authService->verifyUser($user);
            $refreshToken = $this->authService->generateRefreshToken($user);
            return $this->respondWithToken($token, $refreshToken);
        }

        return $this->sendResponse(["message" => __("auth.token.invalid")], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Request to send reset password email
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->getUserByEmail($validated["email"]);
        if ($user) {
            $this->emailVerifyService->sendResetPasswordEmail($user);
            return $this->sendResponse(["message" => __("mail.send.success", ["type" => "Reset password"]),]);
        }

        return $this->sendError(["error" => __("mail.not_found")], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Reset password
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $user = $this->userService->updateUser($user->id, $validated);
        auth()->logout(true);
        if ($user) {
            return $this->sendResponse(["message" => __("auth.reset_password")]);
        }

        return $this->sendError(["error" => __("auth.token.invalid")], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $refreshToken)
    {
        return $this->sendResponse([
            "access_token" => $token,
            "refresh_token" => $refreshToken,
            "token_type" => "bearer",
            "expires_in" => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
