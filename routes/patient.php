<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\PatientProfileController;
use App\Http\Controllers\Patient\PatientStatsController;

Route::middleware(['auth:sanctum', 'isPatient', 'verified'])->prefix('patient')->group(function () {


    // Show ALL my appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show']);

    

    // stats
    Route::get('/dashboard', [PatientStatsController::class, 'index']);

    Route::get('/profile', [PatientProfileController::class, 'show']);
    Route::post('/profile', [PatientProfileController::class, 'update']);

    Route::post('/appointments', [AppointmentController::class, 'store']);
Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
Route::put('/appointments/{id}/complete', [AppointmentController::class, 'complete']);

});
