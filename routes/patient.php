<?php

use App\Http\Controllers\Patient\PatientAppointmentController;

Route::middleware(['auth:sanctum', 'isPatient'])->prefix('patient')->group(function () {

    // Book appointment
    Route::post('/appointments', [PatientAppointmentController::class, 'book']);

    // Show ALL my appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index']);

    // Show last 3 of MY appointments
    Route::get('/appointments/latest', [PatientAppointmentController::class, 'latest']);

    // Show single appointment
    Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show']);

    // Cancel my appointment
    Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel']);
});
