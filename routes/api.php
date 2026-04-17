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
    });

    Route::middleware(['jwt'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('detail', [AuthController::class, 'getDetail']);
            Route::get('profile', [AuthController::class, 'getProfile']);
            Route::get('get-list', [UserController::class, 'get']);
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
