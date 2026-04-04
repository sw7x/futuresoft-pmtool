<?php
use Illuminate\Support\Facades\Route;

use Modules\Messaging\Http\Controllers\MessagingController;




    

Route::get('/messaging', [MessagingController::class,'edit'])->name('edit');






