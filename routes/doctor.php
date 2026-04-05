<?php
use App\Http\Controllers\Doctor\DoctorAppointmentController;

Route::middleware(['auth:sanctum', 'isDoctor'])->prefix('doctor')->group(function () {

    // Show ALL my appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index']);

    // Show upcoming appointments
    Route::get('/appointments/upcoming', [DoctorAppointmentController::class, 'upcoming']);

    // Accept appointment
    Route::post('/appointments/{appointment}/accept', [DoctorAppointmentController::class, 'accept']);

    // Reject appointment
    Route::post('/appointments/{appointment}/reject', [DoctorAppointmentController::class, 'reject']);

    // Cancel appointment (doctor cancellation)
    Route::post('/appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel']);

    // Mark appointment completed
    Route::post('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete']);
});
