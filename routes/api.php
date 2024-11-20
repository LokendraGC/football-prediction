<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\ValidUser;
use Illuminate\Support\Facades\Auth;
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



Route::controller(ResetPasswordController::class)->group(function () {

    Route::post('/forgot-password',  'resetPassword');

    // Reset password page (get route with token)
    Route::get('/reset-password/{token}', 'passwordReset')->name('password.reset');

    // Password update (post route with token)
    Route::post('/reset-password/{token}', 'passwordUpdate')->name('password.update');
});


// Auth::routes(['verify' => true]);

// CRUD OF MATCH AND TEAMS
Route::middleware([ValidUser::class, 'auth:sanctum', EnsureEmailIsVerified::class])->group(function () {
    Route::post('insert-matches', [MatchController::class, 'store']);

    // Route::post('insert-teams', [TeamController::class, 'storeTeam']);

    Route::post('teams', [TeamController::class, 'storeTeam']);
    Route::get('get-teams', [TeamController::class, 'fetchTeam']);
    Route::get('edit-team/{id}', [TeamController::class, 'editTeam']);
    Route::post('update-team/{id}', [TeamController::class, 'updateTeam']);
    Route::delete('delete-team/{id}', [TeamController::class, 'deleteTeam']);

    Route::post('insert-match', [MatchController::class, 'storeMatch']);
    Route::get('get-match', [MatchController::class, 'fetchMatch']);
    Route::get('edit-match/{id}', [MatchController::class, 'editMatch']);
    Route::post('update-match/{id}', [MatchController::class, 'updateMatch']);
    Route::delete('delete-match/{match}', [MatchController::class, 'deleteMatch']);
});

// php artisan make:model Todo -mcr
// command -- resource
// post teams == add new team
// get teams == get all teams
// get teams/{id} == get team by id
// put teams/{id} == update team by id
// delete teams/{id} == delete team by id
// patch teams/{id} == update team by id
