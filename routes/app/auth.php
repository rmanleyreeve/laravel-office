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
