<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenceController;

// calendar month view
Route::get('/absences/calendar',[AbsenceController::class,'getCalendar']);

// list
Route::get('/absences',[AbsenceController::class,'listAbsences']);

// add
Route::get('/absences/add',[AbsenceController::class,'getAddAbsence']);
Route::post('/absences/add',[AbsenceController::class,'postAddAbsence']);

// edit (modal)
Route::get('/absences/{id}/edit',[AbsenceController::class,'getEditAbsence']);
Route::post('/absences/{id}/edit',[AbsenceController::class,'postEditAbsence']);

// delete
Route::get('/absences/{id}/delete',[AbsenceController::class,'deleteAbsence']);

// export
Route::get('/absences/{filename}.csv',[AbsenceController::class,'exportAbsences']);

