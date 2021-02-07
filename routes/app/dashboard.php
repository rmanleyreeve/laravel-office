<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// DASHBOARD #############################################################################

Route::get('/dashboard', [DashboardController::class, 'showDashboard'])
    ->middleware('session:ATTENDANCE')
    ->name('home');
