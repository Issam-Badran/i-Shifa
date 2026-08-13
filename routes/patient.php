<?php

use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Doctor\DoctorBookingInfoController;
use App\Http\Controllers\Patient\DoctorListingController;
use App\Http\Controllers\Patient\PatientConsultationsController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientProfileController;

Route::middleware(['auth:sanctum', 'isPatient', 'verified'])->prefix('patient')->group(function () {

    Route::get('/dashboard', [PatientDashboardController::class, 'index']);

    Route::get('/profile', [PatientProfileController::class, 'show']);
    Route::post('/profile', [PatientProfileController::class, 'update']);

    Route::get('/consultations', [PatientConsultationsController::class, 'index']);


    Route::get('/doctors', [DoctorListingController::class, 'index']);
    Route::get('/{id}/booking-info', [DoctorBookingInfoController::class, 'show']);


    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);


});
