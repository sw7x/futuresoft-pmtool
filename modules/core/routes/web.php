<?php
use Illuminate\Support\Facades\Route;

use Modules\Core\Http\Controllers\SettingsController;
use Modules\Core\Http\Controllers\PermissionController;




    

//Route::get('/user-management', [UserManagementController::class,'edit'])->name('edit');



/* settings */
Route::group(['prefix'=>'settings','as'=>'settings.'], function(){
    Route::get('/general',[SettingsController::class, 'loadGeneralPage'])->name('general');
    Route::get('/advanced',[SettingsController::class, 'loadAdvancedPage'])->name('advanced');   
});

/* permissions */
Route::group(['prefix'=>'permissions','as'=>'permissions.'], function(){
    Route::get('/', [PermissionController::class, 'loadPermissions'])->name('index');
    Route::post('/', [PermissionController::class, 'storePermission'])->name('store');
    Route::delete('/{id}', [PermissionController::class, 'deletePermission'])->name('destroy');


    Route::post('/update', [PermissionController::class, 'updatePermissions'])->name('update');
});



