<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {

    Route::get("activitiesChart", [DashboardController::class, 'getActivityChart']);

    Route::get("bmkg/{bmkg}", [DashboardController::class, 'getWeatherData']);
    Route::get("dashboard", [DashboardController::class, 'dashboard']);
    Route::apiResource('users', UserController::class)->parameters([
        'users' => 'id',
    ]);
    Route::apiResource('fields', FieldController::class)->parameters([
        'fields' => 'id',
    ]);
    Route::apiResource('plants', PlantController::class)->parameters([
        'plants' => 'id',
    ]);
    Route::apiResource('activities', ActivityController::class)->parameters([
        'activities' => 'id',
    ]);

    // Rute tambahan reset password (kardena tidak termasuk CRUD default)
    Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword']);
    Route::delete("logout", [AuthController::class, 'LocalLogout']);
    // Tampilkan form edit profile
    Route::get('/profile', [ProfileController::class, 'edit'])

        ->name('profile.edit');

    // Proses update profile
    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});


Route::post("/refresh", [AuthController::class, 'LocalRefreshToken']);
