<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

// date range reports
Route::get('/reports/overall',[ReportController::class,'getOverall']);
Route::post('/reports/overall',[ReportController::class,'postOverall']);

// individual report, select employee
Route::get('/reports/individual',[ReportController::class,'getIndividual']);
Route::post('/reports/individual',[ReportController::class,'postIndividual']);

// amended times
Route::get('/reports/amended',[ReportController::class,'getAmended']);
