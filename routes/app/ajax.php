<?php

use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;

/*
 * all routes use prefix 'ajax' defined in RouteServiceProvider
 */

Route::middleware('ajax:ATTENDANCE')->group(function () {

    // JSON data (AJAX)
    Route::get('/dashboard-attendance', [AjaxController::class, 'getDashboardAttendance']);

    // header notifications (AJAX)
    Route::get('/header-notifications', [AjaxController::class, 'getNotifications']);
    Route::post('/header-notifications', [AjaxController::class, 'updateNotifications']);
    Route::get('/header-notifications-count', [AjaxController::class, 'countNotifications']);

    // header alerts (AJAX)
    Route::get('/header-alerts', [AjaxController::class, 'getAlerts']);

});

// JSON data (AJAX)
Route::get('/data', [AjaxController::class, 'getData'])->middleware('session');

// check username (AJAX)
Route::post('/users/check-username', [AjaxController::class, 'checkUsername'])->middleware('session:USER');

// attendance error check (AJAX)
Route::get('/admin-check-attendance-ajax', [AjaxController::class, 'checkAttendance'])->middleware('ajax:ADMIN');
