<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// DASHBOARD #############################################################################

Route::get ('/dashboard', [DashboardController::class, 'showDashboard'])
    ->middleware('session:ATTENDANCE')
    ->name('home');
