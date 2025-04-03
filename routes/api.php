<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\LinkApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['check.api.key', 'throttle:api'])->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:strict-api');
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:strict-api');

    // Public routes
    Route::get('/links', [LinkApiController::class, 'index']);
    Route::get('/links/pending', [LinkApiController::class, 'pending']);
    Route::get('/links/{link}', [LinkApiController::class, 'show']);

    Route::get('/links/{link}/comments', [CommentApiController::class, 'index']);
    Route::get('/comments/{comment}', [CommentApiController::class, 'show']);

    Route::get('/tags', [TagApiController::class, 'index']);
    Route::get('/tags/search', [TagApiController::class, 'search']);
    Route::get('/tags/{tag:name}', [TagApiController::class, 'show']);

    Route::get('/users/{username}', [UserApiController::class, 'show']);
    Route::get('/users/{username}/links', [UserApiController::class, 'links']);
    Route::get('/users/{username}/comments', [UserApiController::class, 'comments']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'profile']);

        Route::post('/links', [LinkApiController::class, 'store']);
        Route::post('/links/{link}/vote', [LinkApiController::class, 'vote'])
            ->middleware('min.karma:5');
        Route::get('/user/links/voted', [LinkApiController::class, 'userVoted']);

        Route::post('/links/{link}/comments', [CommentApiController::class, 'store'])
            ->middleware('min.karma:5');
        Route::post('/comments/{comment}/vote', [CommentApiController::class, 'vote'])
            ->middleware('min.karma:5');
        Route::get('/user/comments', [CommentApiController::class, 'userComments']);
    });
});
