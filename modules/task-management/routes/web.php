<?php
use Illuminate\Support\Facades\Route;

use Modules\TaskManagement\Http\Controllers\TaskManagementController;




    

Route::get('/task-management', [TaskManagementController::class,'edit'])->name('edit');






