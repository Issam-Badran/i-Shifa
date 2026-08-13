<?php
use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorConsultationsController;
use App\Http\Controllers\Doctor\DoctorPatientsController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorTimeSlotsController;
use App\Http\Controllers\Doctor\DoctorWorkingDaysController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\SpecializationController;

Route::middleware(['auth:sanctum', 'isDoctor', 'verified'])->prefix('doctor')->group(function () {



    Route::get('/working-days', [DoctorWorkingDaysController::class, 'index']);

    Route::put('/working-days/{dayId}', [DoctorWorkingDaysController::class, 'update']);

    Route::get('/time-slots', [DoctorTimeSlotsController::class, 'index']);


    
    Route::post('/time-slots', [DoctorTimeSlotsController::class, 'store']);
    Route::put('/time-slots/{id}', [DoctorTimeSlotsController::class, 'update']);
    Route::delete('/time-slots/{id}', [DoctorTimeSlotsController::class, 'destroy']);


    Route::get('/dashboard', [DoctorDashboardController::class, 'index']);

    Route::get('/patients', [DoctorPatientsController::class, 'index']);

    Route::get('/patients/{id}', [DoctorPatientsController::class, 'show']);

    Route::get('/consultations', [DoctorConsultationsController::class, 'index']);

    
    Route::get('/consultations/stats', [DoctorConsultationsController::class, 'stats']);

    Route::get('/profile', [DoctorProfileController::class, 'show']);
    Route::put('/profile', [DoctorProfileController::class, 'update']);


    Route::put('/appointments/{id}/complete', [AppointmentController::class, 'complete']);
    
    Route::get('/consultations/{id}', [DoctorConsultationsController::class, 'show']);
});
