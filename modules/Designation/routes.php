<?php
use Illuminate\Support\Facades\Route;
use Modules\Designation\Models\Product;

use Modules\Designation\Http\Controllers\ProductController;






Route::middleware('check.stock')->group(function () {
    //Route::post('/products', [ProductController::class, 'store']);

    Route::get('designation', function () {
        return view('designation::admin');
    });

    Route::get('config', function () {
    //return 'dddddsfsdf3';
        return config('designation.max_levels');
    });

    Route::get('recs', function () {
        dd(Product::all());
    //return 'dddddsfsdf3';
    //return config('designation.max_levels');
    });

});



Route::get('/product-index', [ProductController::class,'index'])->name('index');;
Route::get('/product-store', [ProductController::class,'store'])->name('store');;
Route::get('/product-edit', [ProductController::class,'edit'])->name('edit');;
