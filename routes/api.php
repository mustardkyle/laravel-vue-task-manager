<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\TaskStatisticsController;
use App\Http\Controllers\Admin\UserTaskController;

Route::prefix('backend')->group(function () {
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Authenticated Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Task Routes
        Route::prefix('tasks')->group(function () {
            Route::get('/', [TaskController::class, 'index']);
            Route::post('/', [TaskController::class, 'store']);
            Route::put('/{task}', [TaskController::class, 'update']);
            Route::get('/{id}', [TaskController::class, 'show']);
            Route::delete('/{task}', [TaskController::class, 'destroy']);
            Route::post('/reorder', [TaskController::class, 'reorder']);
        });
    });

    // Admin Routes
    Route::middleware(['auth:sanctum', 'check.admin'])->group(function () {
        Route::get('/admin/task/statistics', [TaskStatisticsController::class, 'show']);
        Route::get('/admin/users-tasks', [UserTaskController::class, 'index']);
    });
});