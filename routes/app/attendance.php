<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('session:ATTENDANCE')->group(function () {

    // daily activity for employee (modal)
    Route::get('/attendance/day/{date}/{id}/view',
        [AttendanceController::class, 'getDailyActivity']);

    // weekly attendance
    Route::get('/attendance/week/{year}/{week}',
        [AttendanceController::class, 'getWeeklyAttendance']);

    // monthly attendance
    Route::get('/attendance/month/{year}/{month}',
        [AttendanceController::class, 'getMonthlyAttendance']);

});
