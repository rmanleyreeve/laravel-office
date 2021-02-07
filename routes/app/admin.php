<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('session:ADMIN')->group(function () {

    // amend attendance
    Route::get('/admin/amend-attendance', [AdminController::class, 'getAmendAttendance']);
    Route::post('/admin/amend-attendance', [AdminController::class, 'postAmendAttendance']);
    Route::post('/admin/execute-amend-attendance', [AdminController::class, 'executeAmendAttendance']);

    // attendance error check
    Route::get('/admin/check-attendance', [AdminController::class, 'checkAttendanceErrors']);

    // repair activity log record
    Route::get('/admin/attendance/repair/{id}/{date}', [AdminController::class, 'getRepairErrors']);
    Route::post('/admin/attendance/repair/{id}/{date}', [AdminController::class, 'postRepairErrors']);

    // insert activity record manually
    Route::get('/admin/manual', [AdminController::class, 'getManualEntry']);
    Route::post('/admin/manual', [AdminController::class, 'postManualEntry']);

});

