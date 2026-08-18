<?php

use App\Http\Controllers\Admin\AdminJoinRequestsController ;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminPaymentsController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\DoctorStatusController;
use App\Http\Controllers\Admin\PlatformSettingsController;


Route::middleware(['auth:sanctum', 'isAdmin' , 'verified'])->prefix('admin')->group(function () {



    Route::get('/stats', [AdminStatsController::class, 'stats']);
    Route::get('/users', [AdminUsersController::class, 'index']);
    // Ban user
    Route::post('/users/{user}/ban', [AdminUsersController::class, 'ban']);

    // Unban user
    Route::post('/users/{user}/unban', [AdminUsersController::class, 'unban']);

    Route::get('/join-requests', [AdminJoinRequestsController::class, 'index']);
    Route::post('/join-requests/{id}/approve', [AdminJoinRequestsController::class, 'approve']);
    Route::post('/join-requests/{id}/reject', [AdminJoinRequestsController::class, 'reject']);
    Route::get('/join-requests/{id}', [AdminJoinRequestsController::class, 'show']);

    Route::get('/settings', [PlatformSettingsController::class, 'show']);
    Route::put('/settings', [PlatformSettingsController::class, 'update']);





    Route::get('/doctors/join_requests', [AdminJoinRequestsController ::class, 'pending']);
    Route::get('/doctors/latest_join_requests', [AdminJoinRequestsController ::class, 'latest']);

    // Show ALL appointments
    Route::get('/appointments', [AdminAppointmentController::class, 'index']);

    // Show last 3 appointments
    Route::get('/appointments/latest', [AdminAppointmentController::class, 'latest']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show']);


    // Route::get('/stats', [AdminStatsController::class, 'index']);

    // List all users

        // عرض حالة طبيب محدد
    Route::get('/doctors/{id}/status', [DoctorStatusController::class, 'show']);

    // تعديل حالة الطبيب
    Route::put('/doctors/{id}/status', [DoctorStatusController::class, 'update']);


        

});
