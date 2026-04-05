<?php

use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminAppointmentController;


Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {


    Route::get('/admin/doctors/pending', [AdminDoctorController::class, 'pending']);
    Route::post('/admin/doctors/{id}/approve', [AdminDoctorController::class, 'approve']);
    Route::post('/admin/doctors/{id}/reject', [AdminDoctorController::class, 'reject']);

    // Show ALL appointments
    Route::get('/appointments', [AdminAppointmentController::class, 'index']);

    // Show last 3 appointments
    Route::get('/appointments/latest', [AdminAppointmentController::class, 'latest']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show']);
});
