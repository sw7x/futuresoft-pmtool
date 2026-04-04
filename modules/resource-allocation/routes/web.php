<?php
use Illuminate\Support\Facades\Route;

use Modules\ResourceAllocation\Http\Controllers\ResourceAllocationController;




    

Route::get('/resource-allocation', [ResourceAllocationController::class,'edit'])->name('edit');






