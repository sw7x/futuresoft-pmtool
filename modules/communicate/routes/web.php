<?php
use Illuminate\Support\Facades\Route;

use Modules\Communicate\Http\Controllers\MessageController;
use Modules\Communicate\Http\Controllers\ProjectThreadController;
use Modules\Communicate\Http\Controllers\TaskThreadController;




    




Route::group(['prefix'=>'threads','as'=>'threads.'], function(){

    Route::get('/create',[ProjectThreadController::class, 'createThread'])->name('create');

    Route::get('/projects',[ProjectThreadController::class, 'projectThreadList'])->name('projects');
    Route::get('/tasks',[TaskThreadController::class, 'takThreadList'])->name('tasks');
    
    Route::get('/projects/{id}',[ProjectThreadController::class, 'thread'])->name('single-project');
    Route::get('/tasks/{id}',[TaskThreadController::class, 'thread'])->name('single-task');
});


Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'mailbox'])->name('index');
    Route::get('/read-mail', [MessageController::class, 'readMail'])->name('read-mail');
    Route::get('/compose', [MessageController::class, 'compose'])->name('compose');  
});


