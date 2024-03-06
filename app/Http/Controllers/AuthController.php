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
    ) {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgetPassword', 'refresh']]);
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
        $user = auth()->user();
        if (!$user->email_verified_at) {
            auth()->logout();
            $this->emailVerifyService->sendVerifyEmail($user);
            return response()->json([
                "message" => 'Verification email sent successfully',
            ], Response::HTTP_OK);
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
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $validated = $request->validated();
        if ($this->authService->checkValidToken($validated['refresh_token'])) {
            $token = $this->authService->refreshToken($validated['refresh_token']);
            return $this->respondWithToken($token, $validated['refresh_token']);
        }
        return response()->json([
            "message" => "Invalid refresh token"
        ], Response::HTTP_UNAUTHORIZED);
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
        } else {
            return response()->json([
                'message' => 'Invalid Token'
            ], Response::HTTP_UNAUTHORIZED);
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
        if ($user) {
            $this->emailVerifyService->sendResetPasswordEmail($user);
            return response()->json([
                "message" => 'Reset Password email sent successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid email'
            ], Response::HTTP_BAD_REQUEST);
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
        $user = $this->userService->updateUser($user->id, $validated);
        auth()->logout(true);
        if ($user) {
            return response()->json([
                'message' => 'Reset password successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid Token'
            ], Response::HTTP_UNAUTHORIZED);
        }
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
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
