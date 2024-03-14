<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('forget_password',[AuthController::class,'forgetPassword']);
    Route::get('verify',[AuthController::class,'verify']);
    Route::post('refresh', [AuthController::class,'refresh']);
});

Route::group([
    'middleware' => ['api','auth:api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('reset_password',[AuthController::class,'resetPassword']);
});

Route::resource('posts',PostController::class)->middleware(['api','auth:api'])->except(['create','edit']);
Route::resource('comments',CommentController::class)->middleware(['api','auth:api'])->except(['create','edit']);
Route::resource('reactions',ReactionController::class)->middleware(['api','auth:api'])->except(['create','edit']);
Route::resource('notifications', NotificationController::class)->middleware(['api','auth:api'])->only(['index','update']);

Route::group([
    'middleware' => ['api','auth:api'],
    'prefix' => 'notifications'
], function ($router) {
    Route::get('/', [NotificationController::class,'index']);
    Route::patch('/{notification}',[NotificationController::class,'markAsRead']);
    Route::put('/{notification}',[NotificationController::class,'markAsRead']);
});
