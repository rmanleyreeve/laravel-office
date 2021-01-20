<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// AUTHENTICATION #####################################################################################################

// default
Route::get('/', [AuthController::class,'default'])->name('default');

// login
Route::post('/login', [AuthController::class,'authenticate']);

// logout
Route::get('/logout', [AuthController::class,'logout']);

// forgot password
Route::get('/forgot-password', [AuthController::class,'getForgotPassword']);
Route::post('/forgot-password', [AuthController::class,'postForgotPassword']);

// reset password
Route::get('/reset/{u}/{t}/{h}', [AuthController::class,'getResetPassword']);
Route::post('/reset', [AuthController::class,'postResetPassword']);

// forgot password
Route::get('/change-password', [AuthController::class,'getChangePassword']);
Route::post('/change-password', [AuthController::class,'postChangePassword']);
