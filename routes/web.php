<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;


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
    return view('test');
});


Route::get('/empty/hh', function () {
    //dd('ddd');
    return view('empty');
});


Route::get('/admin/user-tabe', function () {
    return view('admin.user-tabe');
});

Route::get('/admin/dashboard', function () {
    return view('admin.user-tabe'); // You can change this to your actual dashboard view later
})->name('admin.dashboard');



Route::get('/project2', function () {
    //dd('ddd');
    return view('project2');
});


