<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('session:USER')->group(function () {

    // list
    Route::get('/users', [UserController::class, 'getUsers']);

    // add
    Route::get('/users/add', [UserController::class, 'getAddUser']);
    Route::post('/users/add', [UserController::class, 'postAddUser']);

    // view (modal)
    Route::get('/users/{id}/view', [UserController::class, 'viewUser']);

    // edit
    Route::get('/users/{id}/edit', [UserController::class, 'getEditUser']);
    Route::post('/users/{id}/edit', [UserController::class, 'postEditUser']);

    // delete
    Route::get('/users/{id}/delete', [UserController::class, 'deleteUser']);

    // export
    Route::get('/users/{filename}.csv', [UserController::class, 'exportUsers']);

    // activity per user
    Route::get('/users/{id}/activity', [UserController::class, 'getUserActivity']);

    // all activity by date
    Route::get('/users/activity/date/{d}', [UserController::class, 'getActivityByDate']);

    // data (modal)
    Route::get('/users/activity/data/{id}', [UserController::class, 'viewData']);

    // export all activity
    Route::get('/users/activity/export/{filename}.csv', [UserController::class, 'exportActivity']);

});

// profile
Route::get('/users/{id}/profile', [UserController::class, 'getProfile'])->middleware('session:USER,SELF');

// image (modal)
Route::get('/users/{id}/image', [UserController::class, 'getUserImage'])->middleware('session:USER,SELF');
Route::post('/users/{id}/image', [UserController::class, 'postUserImage'])->middleware('session:USER,SELF');

