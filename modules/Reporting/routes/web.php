<?php
use Illuminate\Support\Facades\Route;
use Modules\Reporting\Models\Report;

use Modules\Reporting\Http\Controllers\ReportController;



Route::middleware('check.report')->group(function () {

    Route::get('report-view', function () {
        return view('reporting::user');
    });

    Route::get('report-config', function () {
        //return 'dddddsfsdf3';
        return config('reporting.reporting_max_levels');
    });

    Route::get('report-recs', function () {
        dd(Report::all());
        //return 'dddddsfsdf3';
        //return config('reporting.max_levels');
    });

    Route::get('/report-index', [ReportController::class,'index'])->name('index');
    Route::get('/report-store', [ReportController::class,'store'])->name('store');
    Route::get('/report-edit', [ReportController::class,'edit'])->name('edit');

});




