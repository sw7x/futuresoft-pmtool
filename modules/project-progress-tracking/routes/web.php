<?php
use Illuminate\Support\Facades\Route;

use Modules\ProjectProgressTracking\Http\Controllers\ProjectProgressTrackingController;




    

Route::get('/project-progress-tracking', [ProjectProgressTrackingController::class,'edit'])->name('edit');






