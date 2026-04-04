<?php
use Illuminate\Support\Facades\Route;

use Modules\Timesheet\Http\Controllers\TimesheetController;




    

Route::get('/timesheet', [TimesheetController::class,'edit'])->name('edit');






