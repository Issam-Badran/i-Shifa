<?php

use App\Http\Controllers\Api\ApiRegisteredUserController;
use App\Http\Controllers\Auth\ApiAuthenticatedSessionController;
use App\Http\Controllers\Auth\ApiEmailVerificationController;
use App\Http\Controllers\Auth\ApiNewPasswordController;
use App\Http\Controllers\Auth\ApiPasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [ApiRegisteredUserController::class, 'store'])
    ->middleware('api.guest')
    ->name('register');

Route::post('/login', [ApiAuthenticatedSessionController::class, 'store'])
    ->middleware('api.guest')
    ->name('login');

Route::post('/forgot-password', [ApiPasswordResetLinkController::class, 'store'])
    ->middleware('api.guest')
    ->name('password.email');

Route::post('/reset-password', [ApiNewPasswordController::class, 'store'])
    ->middleware('api.guest')

    ->name('password.reset');

Route::post('/email/verification-notification', [ApiEmailVerificationController::class, 'send'])
    ->middleware('auth:sanctum')
    ->name('verification.send');

Route::post('/email/verify', [ApiEmailVerificationController::class, 'verify'])
    ->middleware('auth:sanctum')
    ->name('verification.verify');


Route::get('/logout', [ApiAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')

    ->name('logout');

    

// I didn't test the verify and verification notification routes