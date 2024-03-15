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

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forget_password', [AuthController::class, 'forgetPassword']);
        Route::get('verify', [AuthController::class, 'verify']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        Route::group([
            'middleware' => 'auth:api'
        ], function ($router) {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('me', [AuthController::class, 'me']);
            Route::post('reset_password', [AuthController::class, 'resetPassword']);
        });
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::group([
            'prefix' => 'posts',
            'as' => 'posts.'
        ], function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::post('/', [PostController::class, 'store'])->name('store');
            Route::patch('/{post}', [PostController::class, 'update'])->name('update');
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        });
    
        Route::group([
            'prefix' => 'comments',
            'as' => 'comments.'
        ], function () {
            Route::get('/', [CommentController::class, 'index'])->name('index');
            Route::post('/', [CommentController::class, 'store'])->name('store');
            Route::patch('/{comment}', [CommentController::class, 'update'])->name('update');
            Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        });
    
        Route::group([
            'prefix' => 'reactions',
            'as' => 'reactions.'
        ], function () {
            Route::get('/', [ReactionController::class, 'index'])->name('index');
            Route::post('/', [ReactionController::class, 'store'])->name('store');
            Route::patch('/{reaction}', [ReactionController::class, 'update'])->name('update');
            Route::delete('/{reaction}', [ReactionController::class, 'destroy'])->name('destroy');
        });
    
        Route::group([
            'prefix' => 'notifications',
            'as' => 'notifications.'
        ], function ($router) {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::patch('/{notification}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        });
    });
});
