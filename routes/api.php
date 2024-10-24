<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\ValidUser;
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



Route::controller(AdminController::class)->group(function () {
    Route::post('admin/login', 'admin_login');
});



Route::controller(PasswordResetController::class)->group(function () {
    Route::post('send-password-reset-email', 'send_reset_password');
    Route::post('reset-password/{token}', 'reset');
});


// CRUD OF MATCH AND TEAMS
Route::middleware([ValidUser::class, 'auth:sanctum', EnsureEmailIsVerified::class])->group(function () {
    Route::post('insert-matches', [MatchController::class, 'store']);

    Route::post('insert-teams', [TeamController::class, 'storeTeam']);
    Route::get('get-teams', [TeamController::class, 'fetchTeam']);
    Route::get('edit-team/{id}', [TeamController::class, 'editTeam']);
    Route::post('update-team/{id}', [TeamController::class, 'updateTeam']);
    Route::delete('delete-team/{id}', [TeamController::class, 'deleteTeam']);


});
