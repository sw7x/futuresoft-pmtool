<?php
use Illuminate\Support\Facades\Route;

use Modules\Timesheet\Http\Controllers\TimesheetController;




    

//Route::get('/timesheet', [TimesheetController::class,'edit'])->name('edit');


/* timesheet */
Route::group(['prefix'=>'timesheets','as'=>'timesheets.'], function(){
    Route::get('/manager-timesheet-list',[TimesheetController::class, 'managerTimesheetList'])->name('manager-timesheet-list');
    Route::get('/my-timesheet-list',[TimesheetController::class, 'myTimesheetList'])->name('my-timesheet-list');



    Route::get('/create',[TimesheetController::class, 'createTimesheet'])->name('create');
    Route::get('/view',[TimesheetController::class, 'viewTimesheet'])->name('view');
    Route::get('/approve',[TimesheetController::class, 'approveTimesheet'])->name('approve');
});



