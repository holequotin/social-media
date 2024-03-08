<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
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
