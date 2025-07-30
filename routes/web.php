<?php

use App\Http\Controllers\ProcessStatusController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\InsuranceHomeController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->middleware('throttle:5,1'); // 5 attempts per minute
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/reports',[DocumentController::class, 'reports']);
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
        Route::post('/update', [DocumentController::class, 'addCourierDetails'])->name('courier.update');
        // Route::get('/proceed', [DocumentController::class, 'getBulkReview'])->name('accounts.selected');
        Route::post('/proceed', [DocumentController::class, 'bulkReview'])->name('accounts.proceed');
        Route::post('/moved', [DocumentController::class, 'addRmaDetails'])->name('accounts.moved');
        Route::get('/{approvalType}/download', [DocumentController::class, 'downloadPDF'])->name('accounts.download');
    });
    
    Route::get('document/trashed', [DocumentController::class, 'trashedDocuments'])->name('accounts.trash');
    Route::post('/vendor/upload', [DocumentController::class, 'uploadVendorData'])->name('vendor.upload');
    Route::get('document/{id}/{type}/{dtype}', [DocumentController::class, 'viewHistory'])->name('document.history');
    Route::post('document/remove', [DocumentController::class, 'removeDocument'])->name('document.remove');
    Route::post('document/restore', [DocumentController::class, 'restoreDocument'])->name('document.restore');
    Route::get('/get-document-details/{type}/{id}', [DocumentController::class, 'getDocumentDetails']);
    Route::post('/courier/check-awb', [DocumentController::class, 'checkAwb'])->name('courier.checkAwb');
    Route::post('document/dispatchremove', [DocumentController::class, 'removeDispatchesDocument'])->name('document.dispatchremove');
    Route::post('document/update', [DocumentController::class, 'statusUpdate'])->name('document.update');
    Route::get('dispatches/{type}', [DocumentController::class,'getDispatches'])->name('dispatches');
    Route::post('/dispatches/{type}/filter', [DocumentController::class, 'filterDispatches'])->name('dispatches.filter');
    Route::get('/dispatches/clear/{type}', [DocumentController::class, 'clearFilters'])->name('dispatches.clear');
    Route::get('dispatches/edit/{id}', [DocumentController::class,'editDispatches'])->name('dispatches.edit');
    Route::get('dispatches/view/{id}', [DocumentController::class,'viewDispatches'])->name('dispatches.view');
    Route::get('dispatches/check-status/{id}', [DocumentController::class, 'checkDispatchStatus']);
    Route::post('dispatches', [DocumentController::class,'updateCourier'])->name('dispatched');
    Route::post('dispatches/update', [DocumentController::class, 'dispatchDetails'])->name('dispatches.update');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('reports', [DocumentController::class,'export'])->name('reports');
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
    Route::get('users/activities', [UserController::class, 'userActivity'])->name('users.activities');
    Route::patch('/requests/{id}/move-to-rma', [RequestController::class, 'moveToRMA'])->name('requests.moveToRMA');

    Route::resource('vendor', VendorController::class);
    Route::resource('couriers', CourierController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    // Route::resource('branches', BranchController::class);
    Route::resource('emails', EmailController::class);
    Route::resource('uploads', UploadController::class)->only(['index', 'create', 'store']);
    Route::get('uploads/{upload}/download', [UploadController::class, 'download'])->name('uploads.download');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/activity', [UserController::class, 'userActivity'])->name('users.activity');
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

    //insurance
    Route::get('insurance/dashboard',[InsuranceHomeController::class,'index'])->name('insurance_dashboard');
    Route::get('insurance/claim_forms',[InsuranceHomeController::class,'list'])->name('insurance_list');
    Route::get('insurance/create_insurance',[InsuranceHomeController::class,'create'])->name('create_insurance');
    Route::post('save_claim_details',[InsuranceHomeController::class,'store'])->name('save_claim_details');
    Route::get('insurance/view_claim_details/{id}',[InsuranceHomeController::class,'show'])->name('view_claim_details');
    Route::get('insurance/edit_claim_details/{spec}/{id}',[InsuranceHomeController::class,'edit'])->name('edit_claim_details');


    Route::get('insurance/download_claim_form/{id}',[InsuranceHomeController::class,'download_claim_form'])->name('download_claim_form');
    Route::get('insurance/download_checklist/{id}',[InsuranceHomeController::class,'download_checklist'])->name('download_checklist');
    Route::post('insurance/import_claim_data',[InsuranceHomeController::class,'import_claim_data'])->name('import_claim_data');
    Route::post('update_claim_details/{id}',[InsuranceHomeController::class,'update'])->name('update_claim_details');

    Route::post('save_claim_checklist',[InsuranceHomeController::class,'save_claim_checklist'])->name('save_claim_checklist');
    Route::post('save_nominee_details',[InsuranceHomeController::class,'save_nominee_details'])->name('save_nominee_details');

    Route::post('save_documents',[InsuranceHomeController::class,'save_documents'])->name('save_documents');
    Route::post('update_documents',[InsuranceHomeController::class,'update_documents'])->name('update_documents');


    Route::get('insurance/audit-logs',[InsuranceHomeController::class,'audit'])->name('audit');
    Route::get('insurance/leads-report',[InsuranceHomeController::class,'report'])->name('leads_report');
    Route::post('/audit/download-claim', [InsuranceHomeController::class, 'downloadClaim'])->name('audit.download.claim');
    Route::post('/audit/download-checklist', [InsuranceHomeController::class, 'downloadChecklist'])->name('audit.download.checklist');

    Route::get('insurance/settings',[InsuranceHomeController::class,'settings'])->name('insurance_settings');
    Route::post('insurance/add_new_insurance_item',[InsuranceHomeController::class,'add_new_insurance_item'])->name('add_new_insurance_item');

   
});
