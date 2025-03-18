<?php

use App\Http\Controllers\ProcessStatusController;


// Route::resource('process_status', ProcessStatusController::class);

 Route::get('/', function () {
//     return 123;
      return view('home');
 });

Route::get('/sample', function () {
//     return 123;
    return view('sample.index');
});
Auth::routes();
// ProcessStatus resource routes for the ProcessStatusController
Route::get('process_status', [ProcessStatusController::class,'index'])->name('process_status.index');
Route::get('process_status/create', [ProcessStatusController::class,'create'])->name('process_status.create');
Route::post('process_status/store', [ProcessStatusController::class, 'store'])->name('process_status.store');
Route::get('process_status/edit/{id}', [ProcessStatusController::class,'edit'])->name('process_status.edit');
Route::put('process_status/{id}', [ProcessStatusController::class, 'update'])->name('process_status.update');
Route::get('process_status/show/{id}', [ProcessStatusController::class,'show'])->name('process_status.show');
Route::delete('process_status/{id}', [ProcessStatusController::class, 'destroy'])->name('process_status.destroy');
//  Route::post('/ProcessStatus/store', function ()
//  {
// Route::get('validate_gst', [ProcessStatusController::class,'validate_gst'])->name('validate_gst');
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);

Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assignRole');
Route::post('users/{user}/permissions', [UserController::class, 'assignPermission'])->name('users.assignPermission');
