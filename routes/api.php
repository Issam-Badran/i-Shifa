<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum', 'isAdmin'])->get('/admin/test', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

Route::middleware(['auth:sanctum', 'isDoctor'])->get('/doctor/test', function () {
    return response()->json(['message' => 'Welcome Doctor']);
});

Route::middleware(['auth:sanctum', 'isPatient'])->get('/patient/test', function () {
    return response()->json(['message' => 'Welcome Patient']);
});


Route::get('/test', function(){
    return "ngrok is working";
    });


require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/patient.php';
require __DIR__.'/doctor.php';
