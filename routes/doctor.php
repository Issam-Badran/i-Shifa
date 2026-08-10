<?php
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorTimeSlotsController;
use App\Http\Controllers\Doctor\DoctorWorkingDaysController;

Route::middleware(['auth:sanctum', 'isDoctor', 'verified'])->prefix('doctor')->group(function () {

    // Show ALL my appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index']);

    // Show upcoming appointments
    Route::get('/appointments/upcoming', [DoctorAppointmentController::class, 'upcoming']);

    
    // Mark appointment completed
    Route::post('/appointments/{appointment}/complete', [DoctorAppointmentController::class, 'complete']);


    Route::get('/working-days', [DoctorWorkingDaysController::class, 'index']);

    Route::put('/working-days/{dayId}', [DoctorWorkingDaysController::class, 'update']);

    Route::get('/time-slots', [DoctorTimeSlotsController::class, 'index']);


    
    Route::post('/time-slots', [DoctorTimeSlotsController::class, 'store']);
    Route::put('/time-slots/{id}', [DoctorTimeSlotsController::class, 'update']);
    Route::delete('/time-slots/{id}', [DoctorTimeSlotsController::class, 'destroy']);

});
