<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModuleActionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserGoalController;
use App\Http\Controllers\Api\CalorieCalculationController;
use App\Http\Controllers\Api\FoodCategoryController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\FoodRatingController;
use App\Http\Controllers\Api\MealPlanController;

Route::middleware(['api', 'cors'])->group(function () {

    // ── AUTH ────────────────────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::prefix('user')->group(function () {
        Route::post('forgot-password', [UserController::class, 'changePassword']);
    });

    // ── FOODS (public — Python cũng gọi endpoint này) ───────────────────────
    Route::prefix('foods')->group(function () {
        Route::get('/', [FoodController::class, 'index']);
        Route::get('{id}', [FoodController::class, 'show']);
        Route::get('category/{categoryId}', [FoodController::class, 'byCategory']);
    });

    Route::prefix('food-categories')->group(function () {
        Route::get('/', [FoodCategoryController::class, 'index']);
        Route::get('{id}', [FoodCategoryController::class, 'show']);
    });

    // ── AUTHENTICATED ROUTES ─────────────────────────────────────────────────
    Route::middleware(['jwt'])->group(function () {

        // User
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

        // Module action
        Route::prefix('module-action')->group(function () {
            Route::post('create', [ModuleActionController::class, 'createModuleAction']);
        });

        // User Goals
        Route::prefix('goals')->group(function () {
            Route::get('/', [UserGoalController::class, 'index']);
            Route::get('active', [UserGoalController::class, 'active']);
            Route::post('/', [UserGoalController::class, 'store']);
            Route::put('{id}', [UserGoalController::class, 'update']);
            Route::delete('{id}', [UserGoalController::class, 'destroy']);
        });

        // Calorie Calculation
        Route::prefix('calorie')->group(function () {
            Route::post('calculate', [CalorieCalculationController::class, 'calculate']);
            Route::get('latest', [CalorieCalculationController::class, 'latest']);
        });

        // Food Categories (admin write)
        Route::prefix('food-categories')->group(function () {
            Route::post('/', [FoodCategoryController::class, 'store']);
            Route::put('{id}', [FoodCategoryController::class, 'update']);
            Route::delete('{id}', [FoodCategoryController::class, 'destroy']);
        });

        // Foods (admin write)
        Route::prefix('foods')->group(function () {
            Route::post('/', [FoodController::class, 'store']);
            Route::put('{id}', [FoodController::class, 'update']);
            Route::delete('{id}', [FoodController::class, 'destroy']);
            // Food images
            Route::post('{id}/images', [FoodController::class, 'uploadImages']);
            Route::delete('{id}/images/{imageId}', [FoodController::class, 'destroyImage']);
        });

        // Food Ratings
        Route::prefix('food-ratings')->group(function () {
            Route::get('my', [FoodRatingController::class, 'myRatings']);
            Route::get('food/{foodId}', [FoodRatingController::class, 'byFood']);
            Route::get('matrix', [FoodRatingController::class, 'ratingMatrix']);
            Route::post('rate', [FoodRatingController::class, 'rate']);
        });

        // Meal Plans (AI generated)
        Route::prefix('meal-plans')->group(function () {
            Route::get('/', [MealPlanController::class, 'index']);
            Route::get('active', [MealPlanController::class, 'active']);
            Route::post('generate', [MealPlanController::class, 'generate']);
        });
    });
});
