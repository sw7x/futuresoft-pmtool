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
use App\Http\Controllers\PermissionController;
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












Route::get('/', function () {return view('welcome');});

Route::get('/db', [PageController::class,'index2'])->name('index');


Route::get('/test', function () {return view('test');});

Route::get('/empty', function () {return view('empty');});

Route::get('/info', function() {  phpinfo();});


Route::get('/404', [PageController::class, 'page404'])->name('404');

//////////////////////////////

//Route::get('/dashboard', [PageController::class, 'index'])->name('dashboard');


//Route::group(['prefix'=>'project','as'=>'project.'], function(){
/* 
Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'mailbox'])->name('index');
    Route::get('/read-mail', [MessageController::class, 'readMail'])->name('read-mail');
    Route::get('/compose', [MessageController::class, 'compose'])->name('compose');  
}); 
*/




//Route::get('/profile', [UserController::class, 'profile'])->name('profile');

//Route::get('/', ['as'=>'dashboard','uses'=>'PageController@index']);




/* permissions 
Route::group(['prefix'=>'permissions','as'=>'permissions.'], function(){
    Route::get('/', [PermissionController::class, 'loadPermissions'])->name('index');
    Route::post('/', [PermissionController::class, 'storePermission'])->name('store');
    Route::delete('/{id}', [PermissionController::class, 'deletePermission'])->name('destroy');


    Route::post('/update', [PermissionController::class, 'updatePermissions'])->name('update');
});
*/



/* users 
Route::group(['prefix'=>'users','as'=>'users.'], function(){
    Route::get('/',[UserController::class,'users'])->name('index');
    Route::get('/create',[UserController::class,'createUsers'])->name('create');
    
    

    Route::get('/manage-designations',[DesignationController::class,'designationManage'])->name('manage-designations');

    Route::get('/view-designations',[DesignationController::class,'viewDesignations'])->name('view-designations');
    Route::get('/assign-designations',[DesignationController::class,'assignDesignations'])->name('assign-designations');
    Route::get('/{id}',[UserController::class,'viewSingleUser'])->name('view-single');
});
*/


/* project
Route::group(['prefix' => 'project','as' => 'project.'], function () {   
    Route::post('login', ['as'=>'login','uses'=>'ProjectController@login']);
    Route::post('create', ['as'=>'create','uses'=>'ProjectController@create']);
    Route::get('create', ['as'=>'create','uses'=>'ProjectController@create']);
    Route::get('/', ['as'=>'list','uses'=>'ProjectController@index']);
    Route::get('enroll-employees','ProjectController@assignEmployees');
});
*/









/**/

// Route::group(['prefix'=>'projects','as'=>'projects.'], function(){
//     //Route::post('login', [ProjectController::class,'login'])->name('login');
//     //Route::post('create', [ProjectController::class,'create'])->name('create');


//     //Route::get('create', [ProjectController::class,'create'])->name('create');
//     Route::get('/', [ProjectController::class,'index'])->name('list');
//     Route::get('/create', [ProjectController::class,'createProject'])->name('create');
//     Route::get('/enroll-employees', [ProjectController::class,'assignEmployees'])->name('enroll-employees');
//     Route::get('/timeline', [ProjectController::class,'viewTimeline'])->name('timeline');
    


//     /* cost management */
//     Route::group(['prefix'=>'invoices','as'=>'invoices.'], function(){
//         Route::get('/',[CostController::class, 'invoices'])->name('list');
//         Route::get('/create',[CostController::class, 'createInvoice'])->name('create');
//         Route::get('/{id}',[CostController::class, 'singleInvoice'])->name('single');
//     });


//     /* client */
//     Route::group(['prefix'=>'clients','as'=>'clients.'], function(){
//         Route::get('/',[ClientController::class, 'client'])->name('list');
//         //Route::post('/clients/create',[ClientController::class, 'createClient'])->name('clients.create');
//         Route::get('/create',[ClientController::class, 'createClient'])->name('create');
//         Route::get('/{id}',[ClientController::class, 'singleClient'])->name('single');
//     });

//     Route::get('/{id}', [ProjectController::class,'singleProject'])->name('single');

// });


/**/







/* threads
Route::group(['prefix'=>'threads','as'=>'threads.'], function(){

    Route::get('/create',[ProjectController::class, 'createThread'])->name('create');

    Route::get('/projects',[ProjectController::class, 'projectThreadList'])->name('projects');
    Route::get('/tasks',[TaskController::class, 'takThreadList'])->name('tasks');
    
    Route::get('/projects/{id}',[ProjectController::class, 'thread'])->name('single-project');
    Route::get('/tasks/{id}',[TaskController::class, 'thread'])->name('single-task');
});
*/



/* reporting 
Route::group(['prefix'=>'reports','as'=>'reports.'], function(){
    Route::get('/project-timings-by-designation',[ReportController::class, 'projectTimingsByDesignation'])->name('project-timings-by-designation');
    Route::get('/designation-timings-by-project',[ReportController::class, 'DesignationTimingsByProject'])->name('designation-timings-by-project');
    Route::get('/project-timings-by-employee',[ReportController::class, 'ProjectTimingsByEmployee'])->name('project-timings-by-employee');
    Route::get('/employee-timings-by-project',[ReportController::class, 'EmployeeTimingsByProject'])->name('employee-timings-by-project');
    


    Route::get('/dev-workload',[ReportController::class, 'devWorkloadReport'])->name('dev-workload');
    Route::get('/pm-workload',[ReportController::class, 'pmWorkloadReport'])->name('pm-workload');



});
*/


/* timesheet 
Route::group(['prefix'=>'timesheets','as'=>'timesheets.'], function(){
    Route::get('/manager-timesheet-list',[TimesheetController::class, 'managerTimesheetList'])->name('manager-timesheet-list');
    Route::get('/my-timesheet-list',[TimesheetController::class, 'myTimesheetList'])->name('my-timesheet-list');



    Route::get('/create',[TimesheetController::class, 'createTimesheet'])->name('create');
    Route::get('/view',[TimesheetController::class, 'viewTimesheet'])->name('view');
    Route::get('/approve',[TimesheetController::class, 'approveTimesheet'])->name('approve');
});
*/



/* task 
Route::group(['prefix'=>'tasks','as'=>'tasks.'], function(){
    Route::get('/manage',[TaskController::class, 'taskManage'])->name('manage');
    Route::get('/view',[TaskController::class, 'taskView'])->name('view');
    Route::get('/assign-developers',[TaskController::class, 'assignEmployees'])->name('assign-developers');

    //Route::get('/{id}',[TaskController::class, 'taskViewSingle'])->name('view-single');
    //Route::get('/{id}/edit',[TaskController::class, 'taskEditSingle'])->name('edit-single');


    Route::get('/{id}',[TaskController::class, 'taskViewSingle'])->name('view-single')->where('id', '[0-9]+');
    Route::get('/{id}/edit',[TaskController::class, 'taskEditSingle'])->name('edit-single')->where('id', '[0-9]+');
});
*/




/* settings
Route::group(['prefix'=>'settings','as'=>'settings.'], function(){
    Route::get('/general',[SettingsController::class, 'loadGeneralPage'])->name('general');
    Route::get('/advanced',[SettingsController::class, 'loadAdvancedPage'])->name('advanced');   
}); */