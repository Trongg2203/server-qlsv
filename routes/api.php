<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModuleActionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserGoalController;

Route::middleware(['api', 'cors'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::prefix('user')->group(function () {
        Route::post('forgot-password', [UserController::class, 'changePassword']);
    });

    Route::middleware(['jwt'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('detail', [AuthController::class, 'getDetail']);
            Route::get('profile', [AuthController::class, 'getProfile']);
            Route::get('all-active', [UserController::class, 'getAllActive']);
            Route::get('list', [UserController::class, 'get']);
            Route::delete('delete/{id}', [UserController::class, 'delete']);
        });

        Route::prefix('user-goal')->group(function () {
            Route::post('create', [UserGoalController::class, 'createUserGoal']);
            Route::get('get-by-self', [UserGoalController::class, 'getBySelf']);
        });

        Route::prefix('module-action')->group(function () {
            Route::post('create', [ModuleActionController::class, 'createModuleAction']);
        });
    });
});
