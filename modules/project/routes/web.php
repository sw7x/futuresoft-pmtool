<?php

use Illuminate\Support\Facades\Route;

use Modules\Project\Http\Controllers\ProjectController;
use Modules\Project\Http\Controllers\ClientController;




use Modules\Project\Http\Controllers\ProjectInvoicesController;
use Modules\Project\Http\Controllers\ProjectTimelineController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the ProjectServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



/*
Route::prefix('project')->middleware(['web', 'auth'])->group(function () {
    
    // Manage project profile
    Route::resource('projects', ProjectController::class);

    // Clients associated with projects
    Route::resource('clients', ClientController::class);

    // Project Timeline
    Route::get('projects/{project}/timeline', [ProjectTimelineController::class, 'showTimeline'])->name('projects.timeline.show');
    Route::post('projects/{project}/timeline/milestones', [ProjectTimelineController::class, 'setScheduledMilestones'])->name('projects.timeline.milestones.set');
    Route::patch('projects/{project}/timeline/milestones/{milestone}/complete', [ProjectTimelineController::class, 'markMilestoneComplete'])->name('projects.timeline.milestones.complete');


    // Project Invoices
    Route::resource('projects.invoices', ProjectInvoicesController::class)->shallow();

});
*/






Route::prefix('projects')->name('projects.')->middleware(['web'])->group(function(){
//Route::group(['prefix'=>'projects', 'as'=>'projects.', 'middleware'=>['web']], function(){
//Route::group(['prefix'=>'projects','as'=>'projects.'], function(){
    


    //Route::post('login', [ProjectController::class,'login'])->name('login');
    //Route::post('create', [ProjectController::class,'create'])->name('create');


    //Route::get('create', [ProjectController::class,'create'])->name('create');
    Route::get('/', [ProjectController::class,'index'])->name('list');
    Route::get('/create', [ProjectController::class,'createProject'])->name('create');
    ////Route::get('/enroll-employees', [ProjectController::class,'assignEmployees'])->name('enroll-employees');
    ////----Route::get('/timeline', [ProjectController::class,'viewTimeline'])->name('timeline');
    Route::get('/timeline', [ProjectTimelineController::class,'viewTimeline'])->name('timeline');
    Route::get('/enroll-employees', [ProjectController::class,'assignEmployees'])->name('enroll-employees');

    Route::get('/{id}', [ProjectController::class,'singleProject'])->name('single')->where('id', '[0-9]+');


});




/* client */
Route::prefix('clients')->name('clients.')->middleware(['web'])->group(function(){
    Route::get('/',[ClientController::class, 'client'])->name('list');
    //Route::post('/clients/create',[ClientController::class, 'createClient'])->name('clients.create');
    Route::get('/create',[ClientController::class, 'createClient'])->name('create');
    Route::get('/{id}',[ClientController::class, 'singleClient'])->name('single');
});


/* cost management */
Route::prefix('invoices')->name('invoices.')->middleware(['web'])->group(function(){
    Route::get('/',[ProjectInvoicesController::class, 'invoices'])->name('list');
    Route::get('/create',[ProjectInvoicesController::class, 'createInvoice'])->name('create');
    Route::get('/{id}',[ProjectInvoicesController::class, 'singleInvoice'])->name('single');
});