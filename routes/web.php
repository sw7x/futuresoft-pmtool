<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

use App\Http\Controllers\PageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\ProjectController;
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


Route::get('/db', [CarController::class,'index'])->name('index');


Route::get('/test', function () {
    return view('test');
});

Route::get('/empty', function () {
    //dd('ddd');
    return view('empty');
});




//////////////////////////////

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/info', function() {  phpinfo();});



//Route::group(['prefix'=>'project','as'=>'project.'], function(){
Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [PageController::class, 'mailbox'])->name('index');
    Route::get('/read-mail', [PageController::class, 'readMail'])->name('read-mail');
    Route::get('/compose', [PageController::class, 'compose'])->name('compose');    
});




Route::get('/profile', [PageController::class, 'profile'])->name('profile');

//Route::get('/', ['as'=>'dashboard','uses'=>'PageController@index']);
Route::get('/404', [PageController::class, 'page404'])->name('404');

//todo - move into auth routes
Route::get('/login', [PageController::class, 'login'])->name('login');



/* users */
Route::group(['prefix'=>'users','as'=>'users.'], function(){
    Route::get('/',[PageController::class,'users'])->name('index');
    Route::get('/designations',[PageController::class,'designationManage'])->name('designations');
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
    Route::get('enroll-employees', [ProjectController::class,'assignEmployees'])->name('enroll-employees');


    /* client */
    Route::get('/clients',[ClientController::class, 'client'])->name('clients');
    Route::post('/clients/create',[ClientController::class, 'createClient'])->name('clients.create');

    /* cost management */
    Route::get('/invoices',[CostController::class, 'invoices'])->name('invoices');


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
    Route::get('/designation-projectwise-timing',[PageController::class, 'desigProjectwiseTiming'])->name('designation-projectwise-timing');
    Route::get('/developer-projectwise-timing',[PageController::class, 'devProjectwiseTiming'])->name('developer-projectwise-timing');
});



/* timesheet */
Route::group(['prefix'=>'timesheets','as'=>'timesheets.'], function(){
    Route::get('/',[PageController::class, 'listTimesheet'])->name('list');
    Route::get('/submit',[PageController::class, 'submitTimesheet'])->name('submit');
    Route::get('/view',[PageController::class, 'viewTimesheet'])->name('view');
});




/* task */
Route::group(['prefix'=>'tasks','as'=>'tasks.'], function(){
    Route::get('/create',[TaskController::class, 'taskCreate'])->name('create');
    Route::get('/view',[TaskController::class, 'taskView'])->name('view');
    Route::get('/assign-developers',[TaskController::class, 'assignEmployees'])->name('assign-developers');
});

