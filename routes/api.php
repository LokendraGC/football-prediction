<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {

    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::get('user', 'userProfile')->middleware('auth:sanctum');
    Route::get('logout', 'userLogout')->middleware('auth:sanctum');

    Route::get('/send-verify-mail/{email}','sendVerifyMail');

});


Route::post('send-password-reset-email', [PasswordResetController::class, 'send_reset_password']);
Route::post('reset-password/{token}', [PasswordResetController::class, 'reset']);
