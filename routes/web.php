<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);

Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assignRole');
Route::post('users/{user}/permissions', [UserController::class, 'assignPermission'])->name('users.assignPermission');