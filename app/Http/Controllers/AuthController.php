<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\EmailVerifyService;
use App\Services\UserService;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
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
    )
    {
        $this->middleware('auth:api', ['except' => ['login','register','forgetPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request) {
        $validated = $request->validated();
        $user = $this->userService->createUser($validated);
        $this->emailVerifyService->sendVerifyEmail($user);
        return new UserResource($user);
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
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth() -> user();
        if(!$user->email_verified_at){
            auth()->logout();
            $this->emailVerifyService->sendVerifyEmail($user);
            return response()->json([
                "message" => 'Verification email sent successfully',
            ],200);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }
    /**
     * Verify user email
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user)
        {
            $token = $this->authService->verifyUser($user);
            return $this->respondWithToken($token);
        }else{
            return response()->json([
                'message' => 'Invalid Token'
            ],401);
        }
    }

    /**
     * Request to send reset password email
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->getUserByEmail($validated['email']);
        if($user){
            $this->emailVerifyService->sendResetPasswordEmail($user);
            return response()->json([
                "message" => 'Reset Password email sent successfully',
            ]);
        }else{
            return response()->json([
                'message' => 'Invalid email'
            ],400);
        }
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
        $user = $this->userService->updateUser($user->id,$validated);
        auth()->logout(true);
        if($user){
            return response()->json([
                'message' => 'Reset password successfully',
            ],200);
        }else{
            return response()->json([
                'message' => 'Invalid Token'
            ],401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
