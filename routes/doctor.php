<?php
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorTimeSlotsController;
use App\Http\Controllers\Doctor\DoctorWorkingDaysController;

Route::middleware(['auth:sanctum', 'isDoctor', 'verified'])->prefix('doctor')->group(function () {

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


    Route::get('/doctor/working-days', [DoctorWorkingDaysController::class, 'index']);
    Route::put('/doctor/working-days/{dayId}', [DoctorWorkingDaysController::class, 'update']);

    Route::get('/doctor/time-slots', [DoctorTimeSlotsController::class, 'index']);
    Route::post('/doctor/time-slots', [DoctorTimeSlotsController::class, 'store']);
    Route::put('/doctor/time-slots/{id}', [DoctorTimeSlotsController::class, 'update']);
    Route::delete('/doctor/time-slots/{id}', [DoctorTimeSlotsController::class, 'destroy']);

});
