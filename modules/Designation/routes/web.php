<?php
use Illuminate\Support\Facades\Route;

use Modules\Timesheet\Http\Controllers\DesignationController;




    

Route::get('/designation', [DesignationController::class,'edit'])->name('edit');






