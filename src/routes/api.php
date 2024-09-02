<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Auth\AuthController::class)->group(function () {
    Route::post('registration', 'register')->name('auth.registration');
    Route::post('login', 'login')->name('auth.login');
});

Route::post('files/verification', \App\Http\Controllers\FileVerification\FileVerificationController::class)
    ->middleware('auth:sanctum')
    ->name('files.verification');
