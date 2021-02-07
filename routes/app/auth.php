<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// AUTHENTICATION ########################################################################

// login, logout
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);

// forgot password
Route::get('/forgot-password', [LoginController::class, 'getForgotPassword']);
Route::post('/forgot-password', [LoginController::class, 'postForgotPassword']);

// reset password
Route::get('/reset/{u}/{t}/{h}', [LoginController::class, 'getResetPassword']);
Route::post('/reset', [LoginController::class, 'postResetPassword']);

Route::middleware('session')->group(function () {

    // app entrypoint
    Route::get('/', [LoginController::class, 'default'])->name('default');

    // forgot password
    Route::get('/change-password', [LoginController::class, 'getChangePassword']);
    Route::post('/change-password', [LoginController::class, 'postChangePassword']);

});

