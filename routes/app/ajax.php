<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;

// JSON data (AJAX)
Route::get('/dashboard-attendance', [AjaxController::class,'getDashboardAttendance']);

// header notifications (AJAX)
Route::get('/header-notifications', [AjaxController::class,'getNotifications']);
Route::post('/header-notifications', [AjaxController::class, 'updateNotifications']);
Route::get('/header-notifications-count', [AjaxController::class,'countNotifications']);

// header alerts (AJAX)
Route::get('/header-alerts', [AjaxController::class,'getAlerts']);

// JSON data (AJAX)
Route::get('/data', [AjaxController::class,'getData']);

// check username AJAX
Route::post('/check-username', [AjaxController::class,'checkUsername']);

// attendance error check (AJAX)
Route::get('/admin-check-attendance-ajax', [AjaxController::class,'checkAttendance']);
