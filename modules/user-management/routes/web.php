<?php
use Illuminate\Support\Facades\Route;

use Modules\Timesheet\Http\Controllers\UserManagementController;




    

Route::get('/user-management', [UserManagementController::class,'edit'])->name('edit');






