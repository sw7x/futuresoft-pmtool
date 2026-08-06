<?php
use Illuminate\Support\Facades\Route;

//use Modules\Timesheet\Http\Controllers\UserManagementController;

use Modules\Employee\Http\Controllers\UserController;
use Modules\Employee\Http\Controllers\DesignationController;


    

//Route::get('/user-management', [UserManagementController::class,'edit'])->name('edit');



Route::get('/profile', [UserController::class, 'profile'])->name('profile');


/* users */
Route::group(['prefix'=>'users','as'=>'users.'], function(){
    Route::get('/',[UserController::class,'users'])->name('index');
    Route::get('/create',[UserController::class,'createUsers'])->name('create');
    
    

    Route::get('/manage-designations',[DesignationController::class,'designationManage'])->name('manage-designations');

    Route::get('/view-designations',[DesignationController::class,'viewDesignations'])->name('view-designations');
    Route::get('/assign-designations',[DesignationController::class,'assignDesignations'])->name('assign-designations');
    Route::get('/{id}',[UserController::class,'viewSingleUser'])->name('view-single');
});
