<?php

// use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::group(['middleware' => 'guest'], function () {

// Route::get('/login', [AuthController::class, 'view_login'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route::get('/register', [AuthController::class, 'view_register'])->name('register');
// Route::post('/register', [AuthController::class, 'register'])->name('register');
// });

// Route::group(['middleware' => 'auth'], function () {
//     Route::get('home', [AuthController::class, 'home'])->name('home');
//     Route::get('logout', [AuthController::class, 'logout'])->name('logout');
// });


Route::get('verify-mail/{token}',[AuthController::class,'verifyToken']);
Route::view('update-avatar','updateimg');
Route::post('update-avatar/{id}', [AuthController::class,'updateAvatar']);