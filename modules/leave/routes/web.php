<?php
use Illuminate\Support\Facades\Route;
use Modules\Leave\Models\Leave;

use Modules\Leave\Http\Controllers\LeaveController;



/*
Route::group(['prefix'=>'leave','as'=>'leave.', 'middleware'=>'check.leave'], function(){
    Route::get('view', function () {
        return view('leave::view');
    });
    
    Route::get('edit', [LeaveController::class,'edit'])->name('edit');
});
*/





Route::group(['prefix'=>'leave','as'=>'leave.'], function(){
    Route::middleware('check.leave')->group(function () {
        Route::get('view', function () {
            return view('leave::view');
        });
        
        Route::get('edit', [LeaveController::class,'edit'])->name('edit');
        
        Route::get('aaa/{id}', [LeaveController::class,'edit'])->name('edit');
        Route::get('config', [LeaveController::class,'config'])->name('config');
    });

    Route::get('index', [LeaveController::class,'index'])->name('index');
});