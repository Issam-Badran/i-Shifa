<?php

use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\PatientProfileController;
use App\Http\Controllers\Patient\PatientStatsController;

Route::middleware(['auth:sanctum', 'isPatient', 'verified'])->prefix('patient')->group(function () {

    // Book appointment
    Route::post('/appointments', [PatientAppointmentController::class, 'book']);

    // Show ALL my appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show']);

    // Cancel my appointment
    Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel']);

    // stats
    Route::get('/dashboard', [PatientStatsController::class, 'index']);

    Route::get('/profile', [PatientProfileController::class, 'show']);
    Route::post('/profile', [PatientProfileController::class, 'update']);
});
