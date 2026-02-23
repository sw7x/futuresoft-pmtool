<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DesignationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



/*  ======  Artisan routes  -  /routes/web-includes/artisan-commands.php   ============== */

/*  ======  Auth routes     -  /routes/web-includes/auth.php               ============== */

/*  ======  Test routes     -  /routes/web-includes/test.php               ============== */












Route::get('/', function () {
    return view('welcome');
});


Route::get('/db', [PageController::class,'index2'])->name('index');


Route::get('/test', function () {
    return view('test');
});

Route::get('/empty', function () {
    //dd('ddd');
    return view('empty');
});




//////////////////////////////

Route::get('/dashboard', [PageController::class, 'index'])->name('dashboard');
Route::get('/info', function() {  phpinfo();});



//Route::group(['prefix'=>'project','as'=>'project.'], function(){
Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'mailbox'])->name('index');
    Route::get('/read-mail', [MessageController::class, 'readMail'])->name('read-mail');
    Route::get('/compose', [MessageController::class, 'compose'])->name('compose');  
});




Route::get('/profile', [UserController::class, 'profile'])->name('profile');

//Route::get('/', ['as'=>'dashboard','uses'=>'PageController@index']);
Route::get('/404', [PageController::class, 'page404'])->name('404');





/* users */
Route::group(['prefix'=>'users','as'=>'users.'], function(){
    Route::get('/',[UserController::class,'users'])->name('index');
    Route::get('/create',[UserController::class,'createUsers'])->name('create');
    
    

    Route::get('/manage-designations',[DesignationController::class,'designationManage'])->name('manage-designations');

    Route::get('/view-designations',[DesignationController::class,'viewDesignations'])->name('view-designations');
    Route::get('/assign-designations',[DesignationController::class,'assignDesignations'])->name('assign-designations');
Route::get('/{id}',[UserController::class,'viewSingleUser'])->name('view-single');
});



/* project
Route::group(['prefix' => 'project','as' => 'project.'], function () {   
    Route::post('login', ['as'=>'login','uses'=>'ProjectController@login']);
    Route::post('create', ['as'=>'create','uses'=>'ProjectController@create']);
    Route::get('create', ['as'=>'create','uses'=>'ProjectController@create']);
    Route::get('/', ['as'=>'list','uses'=>'ProjectController@index']);
    Route::get('enroll-employees','ProjectController@assignEmployees');
});
*/

Route::group(['prefix'=>'projects','as'=>'projects.'], function(){
    //Route::post('login', [ProjectController::class,'login'])->name('login');
    //Route::post('create', [ProjectController::class,'create'])->name('create');
    

    //Route::get('create', [ProjectController::class,'create'])->name('create');
    Route::get('/', [ProjectController::class,'index'])->name('list');
    Route::get('/create', [ProjectController::class,'createProject'])->name('create');
    Route::get('enroll-employees', [ProjectController::class,'assignEmployees'])->name('enroll-employees');
    


    /* cost management */
    Route::group(['prefix'=>'invoices','as'=>'invoices.'], function(){
        Route::get('/',[CostController::class, 'invoices'])->name('list');
        Route::get('/create',[CostController::class, 'createInvoice'])->name('create');
        Route::get('/{id}',[CostController::class, 'singleInvoice'])->name('single');
    });


    /* client */
    Route::group(['prefix'=>'clients','as'=>'clients.'], function(){
        Route::get('/',[ClientController::class, 'client'])->name('list');
        //Route::post('/clients/create',[ClientController::class, 'createClient'])->name('clients.create');
        Route::get('/create',[ClientController::class, 'createClient'])->name('create');
        Route::get('/{id}',[ClientController::class, 'singleClient'])->name('single');
    });

Route::get('/{id}', [ProjectController::class,'singleProject'])->name('single');

});














/* threads */
Route::group(['prefix'=>'threads','as'=>'threads.'], function(){
    
    Route::get('/create',[ProjectController::class, 'createThread'])->name('create');

    Route::get('/projects',[ProjectController::class, 'projectThreadList'])->name('projects');
    Route::get('/tasks',[TaskController::class, 'takThreadList'])->name('tasks');
    
    Route::get('/projects/{id}',[ProjectController::class, 'thread'])->name('single-project');
    Route::get('/tasks/{id}',[TaskController::class, 'thread'])->name('single-task');




});



/* reporting */
Route::group(['prefix'=>'reports','as'=>'reports.'], function(){
    Route::get('/project-timings-by-designation',[ReportController::class, 'projectTimingsByDesignation'])->name('project-timings-by-designation');
    Route::get('/designation-timings-by-project',[ReportController::class, 'DesignationTimingsByProject'])->name('designation-timings-by-project');
    Route::get('/project-timings-by-employee',[ReportController::class, 'ProjectTimingsByEmployee'])->name('project-timings-by-employee');
    Route::get('/employee-timings-by-project',[ReportController::class, 'EmployeeTimingsByProject'])->name('employee-timings-by-project');



});



/* timesheet */
Route::group(['prefix'=>'timesheets','as'=>'timesheets.'], function(){
    Route::get('/pending',[TimesheetController::class, 'pendingTimesheetList'])->name('pending-list');
    Route::get('/approved',[TimesheetController::class, 'approvedTimesheetList'])->name('approved-list');


    Route::get('/my-pending-list',[TimesheetController::class, 'myPendingTimesheetList'])->name('my-pending-list');
    Route::get('/my-approved-list',[TimesheetController::class, 'myApprovedTimesheetList'])->name('my-approved-list');



    Route::get('/create',[TimesheetController::class, 'createTimesheet'])->name('create');
    Route::get('/view',[TimesheetController::class, 'viewTimesheet'])->name('view');
});




/* task */
Route::group(['prefix'=>'tasks','as'=>'tasks.'], function(){
    Route::get('/manage',[TaskController::class, 'taskManage'])->name('manage');
    Route::get('/view',[TaskController::class, 'taskView'])->name('view');
    Route::get('/assign-developers',[TaskController::class, 'assignEmployees'])->name('assign-developers');

    Route::get('/{id}',[TaskController::class, 'taskViewSingle'])->name('view-single');
    Route::get('/{id}/edit',[TaskController::class, 'taskEditSingle'])->name('edit-single');

});





/* settings */
Route::group(['prefix'=>'settings','as'=>'settings.'], function(){
    Route::get('/general',[SettingsController::class, 'loadGeneralPage'])->name('general');
    Route::get('/advanced',[SettingsController::class, 'loadAdvancedPage'])->name('advanced');   
});