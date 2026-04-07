<?php

use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminUsersController;


Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {


    Route::get('/doctors/join_requests', [AdminDoctorController::class, 'pending']);
    Route::get('/doctors/latest_join_requests', [AdminDoctorController::class, 'latest']);
    Route::post('/doctors/{id}/approve', [AdminDoctorController::class, 'approve']);
    Route::post('/doctors/{id}/reject', [AdminDoctorController::class, 'reject']);

    // Show ALL appointments
    Route::get('/appointments', [AdminAppointmentController::class, 'index']);

    // Show last 3 appointments
    Route::get('/appointments/latest', [AdminAppointmentController::class, 'latest']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show']);


    Route::get('/stats', [AdminStatsController::class, 'index']);

    // List all users
        Route::get('/users', [AdminUsersController::class, 'index']);

        // Ban user
        Route::post('/users/{user}/ban', [AdminUsersController::class, 'ban']);

        // Unban user
        Route::post('/users/{user}/unban', [AdminUsersController::class, 'unban']);



});
