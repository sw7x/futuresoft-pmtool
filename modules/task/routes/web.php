<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\TaskManagementController;
use Modules\Task\Http\Controllers\TaskController;


/*
Route::get('/task-management', [TaskManagementController::class, 'edit'])->name('edit');

Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
    Route::get('/', [TaskController::class, 'index'])->name('list');
    Route::get('/create', [TaskController::class, 'createTask'])->name('create');
    Route::post('/store', [TaskController::class, 'storeTask'])->name('store');
    


    Route::get('/{id}', [TaskController::class, 'singleTask'])->name('single');
    Route::get('/{id}/edit', [TaskController::class, 'editTask'])->name('edit');
    Route::patch('/{id}/update', [TaskController::class, 'updateTask'])->name('update');
    Route::delete('/{id}/delete', [TaskController::class, 'deleteTask'])->name('delete');
});
*/


Route::group(['prefix'=>'tasks','as'=>'tasks.'], function(){
    Route::get('/', [TaskController::class, 'index'])->name('list');
    Route::get('/create', [TaskController::class, 'createTask'])->name('create');
    Route::post('/store', [TaskController::class, 'storeTask'])->name('store');




    //Route::get('/manage',[TaskController::class, 'taskManage'])->name('manage');
    Route::get('/view',[TaskController::class, 'taskView'])->name('view');
    Route::get('/assign-developers',[TaskController::class, 'assignEmployees'])->name('assign-developers');

    
    Route::get('/{id}',[TaskController::class, 'taskViewSingle'])->name('view-single')->where('id', '[0-9]+');
    Route::get('/{id}/edit',[TaskController::class, 'taskEditSingle'])->name('edit-single')->where('id', '[0-9]+');




    //Route::get('/{id}', [TaskController::class, 'singleTask'])->name('single');
    //Route::get('/{id}/edit', [TaskController::class, 'editTask'])->name('edit');
    Route::patch('/{id}/update', [TaskController::class, 'updateTask'])->name('update');
    Route::delete('/{id}/delete', [TaskController::class, 'deleteTask'])->name('delete');

});







