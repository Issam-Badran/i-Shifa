<?php

use App\Http\Controllers\Admin\AdminDoctorController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    Route::get('/admin/doctors/pending', [AdminDoctorController::class, 'pending']);
    Route::post('/admin/doctors/{id}/approve', [AdminDoctorController::class, 'approve']);
    Route::post('/admin/doctors/{id}/reject', [AdminDoctorController::class, 'reject']);

});
