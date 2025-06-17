<?php

use App\Http\Controllers\ProcessStatusController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourierController;

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/sample', function () {
        return view('sample.index');
    });
    Route::get('/accounts-index', function () {
        return view('sample.index');
    });
    Route::get('/accounts-process', function () {
        // exit('1');
        return view('sample.accounts-process');
    });
    Route::get('/accounts-update', function () {
        return view('sample.accounts-update');
    });
    Route::get('/', function () { 
        return redirect(route('login'));
    });
    
    Route::get('documents/proceed', [DocumentController::class, 'getBulkReview'])->name('accounts.selected');
    Route::post('document/filter', [DocumentController::class, 'filter'])->name('document.filter');
    Route::get('document/filter', [DocumentController::class, 'filteredList'])->name('document.filtered');
    Route::prefix('documents')->group(function () {
        Route::get('/{type}', [DocumentController::class, 'index'])->name('accounts.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('accounts.create');
        Route::post('/update', [DocumentController::class, 'addCourierDetails'])->name('courier.update');
        // Route::get('/proceed', [DocumentController::class, 'getBulkReview'])->name('accounts.selected');
        Route::post('/proceed', [DocumentController::class, 'bulkReview'])->name('accounts.proceed');
        Route::post('/moved', [DocumentController::class, 'addRmaDetails'])->name('accounts.moved');
        Route::get('/{approvalType}/download', [DocumentController::class, 'downloadPDF'])->name('accounts.download');
    });
    
    Route::post('document/remove', [DocumentController::class, 'removeDocument'])->name('document.remove');
    Route::post('document/update', [DocumentController::class, 'statusUpdate'])->name('document.update');
    Route::get('dispatches/{type}', [DocumentController::class,'getDispatches'])->name('dispatches');
    Route::get('dispatches/edit/{id}', [DocumentController::class,'editDispatches'])->name('dispatches.edit');
    Route::get('dispatches/view/{id}', [DocumentController::class,'viewDispatches'])->name('dispatches.view');
    Route::post('dispatches', [DocumentController::class,'updateCourier'])->name('dispatched');
    Route::post('dispatches/update', [DocumentController::class, 'dispatchDetails'])->name('dispatches.update');
    
    Route::get('home', [HomeController::class, 'index'])->name('home');
    // Route::get('home', [HomeController::class, 'index'])->name('home');
    // Route::get('home', function () { return view('home'); })->name('home');
    // ProcessStatus resource routes for the ProcessStatusController
    Route::get('process-status', [ProcessStatusController::class,'index'])->name('process_status.index');
    Route::get('process-status/create', [ProcessStatusController::class,'create'])->name('process_status.create');
    Route::post('process-status/store', [ProcessStatusController::class, 'store'])->name('process_status.store');
    Route::get('process-status/edit/{id}', [ProcessStatusController::class,'edit'])->name('process_status.edit');
    Route::put('process-status/{id}', [ProcessStatusController::class, 'update'])->name('process_status.update');
    Route::get('process-status/show/{id}', [ProcessStatusController::class,'show'])->name('process_status.show');
    Route::delete('process-status/{id}', [ProcessStatusController::class, 'destroy'])->name('process_status.destroy');
    
    Route::get('branches', [BranchController::class,'index'])->name('branches.index');
    Route::get('branches/create', [BranchController::class,'create'])->name('branches.create');
    Route::post('branches/store', [BranchController::class, 'store'])->name('branches.store');
    Route::get('branches/edit/{id}', [BranchController::class,'edit'])->name('branches.edit');
    Route::put('branches/{id}', [BranchController::class, 'update'])->name('branches.update');
    Route::get('branches/show/{id}', [BranchController::class,'show'])->name('branches.show');
    Route::delete('branches/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');

    Route::get('departments', [DepartmentController::class,'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentController::class,'create'])->name('departments.create');
    Route::post('departments/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/edit/{id}', [DepartmentController::class,'edit'])->name('departments.edit');
    Route::put('departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::get('departments/show/{id}', [DepartmentController::class,'show'])->name('departments.show');
    Route::delete('departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    //  Route::post('/ProcessStatus/store', function ()
    //  {
    // Route::post('process_status',RoleController::class);

    Route::resource('vendor', VendorController::class);
    Route::resource('couriers', CourierController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    // Route::resource('branches', BranchController::class);
    Route::resource('emails', EmailController::class);
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('users/{user}/roles', [UserController::class, 'assignRole'])->name('users.assignRole');
    Route::post('users/{user}/permissions', [UserController::class, 'assignPermission'])->name('users.assignPermission');
    

    
        // Route::get('/', [UserController::class, 'index'])->name('index');
        // Route::get('/create', [UserController::class, 'create'])->name('create');
        // Route::post('/', [UserController::class, 'store'])->name('store');
        // Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        // Route::put('/{user}', [UserController::class, 'update'])->name('update');
        // Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        // Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])->name('assignRole');
        // Route::post('/{user}/assign-permission', [UserController::class, 'assignPermission'])->name('assignPermission');
  

});
