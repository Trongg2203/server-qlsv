<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['api', 'cors'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware(['jwt'])->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('get-list', [UserController::class, 'get']);
        });
    });
});
