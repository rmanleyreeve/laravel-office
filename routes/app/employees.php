<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

// list
Route::get ('/employees', [EmployeeController::class, 'getEmployees']);

// add
Route::get ('/employees/add', [EmployeeController::class, 'getAddEmployee']);
Route::post ('/employees/add', [EmployeeController::class, 'postAddEmployee']);

// view (modal)
Route::get ('/employees/{id}/view', [EmployeeController::class, 'viewEmployee']);

// edit
Route::get ('/employees/{id}/edit', [EmployeeController::class, 'getEditEmployee']);
Route::post ('/employees/{id}/edit', [EmployeeController::class, 'postEditEmployee']);

// delete
Route::get ('/employees/{id}/delete', [EmployeeController::class, 'deleteEmployee']);

// export
Route::get ('/employees/{filename}.csv', [EmployeeController::class, 'exportEmployees']);

// image
Route::get ('/employees/{id}/image', [EmployeeController::class, 'getEmployeeImage']);




