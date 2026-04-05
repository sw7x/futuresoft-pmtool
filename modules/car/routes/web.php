<?php
use Illuminate\Support\Facades\Route;

use Modules\Car\Http\Controllers\CarController;




    

Route::get('/car', [CarController::class,'edit'])->name('edit');






