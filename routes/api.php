<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExternalPostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('tasks', TaskController::class);

    Route::prefix('external')->group(function () {
        Route::get('/posts', [ExternalPostController::class, 'index']);
        Route::get('/posts/{id}', [ExternalPostController::class, 'show']);
    });

});