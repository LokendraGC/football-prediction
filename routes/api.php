<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {

    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::middleware(['auth:sanctum', EnsureEmailIsVerified::class])->group(function () {
        Route::get('user', 'userProfile');
        Route::get('logout', 'userLogout');
        Route::post('update-avatar', 'updateAvatar');
    });

    Route::get('/send-verify-mail/{email}', 'sendVerifyMail');
});


Route::controller(PasswordResetController::class)->group(function () {
    Route::post('send-password-reset-email', 'send_reset_password');
    Route::post('reset-password/{token}', 'reset');
});
