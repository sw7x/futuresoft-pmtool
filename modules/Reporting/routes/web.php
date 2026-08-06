<?php
use Illuminate\Support\Facades\Route;

use Modules\Reporting\Http\Controllers\ReportController;
use Modules\Reporting\Http\Controllers\DashboardController;



Route::middleware('check.report')->group(function () {

    
    Route::get('report-config', function () {
        //return 'dddddsfsdf3';
        return config('reporting.reporting_max_levels');
    });
});





Route::group(['prefix'=>'reports','as'=>'reports.'], function(){
    Route::get('/project-timings-by-designation',[ReportController::class, 'projectTimingsByDesignation'])->name('project-timings-by-designation');
    Route::get('/designation-timings-by-project',[ReportController::class, 'DesignationTimingsByProject'])->name('designation-timings-by-project');
    Route::get('/project-timings-by-employee',[ReportController::class, 'ProjectTimingsByEmployee'])->name('project-timings-by-employee');
    Route::get('/employee-timings-by-project',[ReportController::class, 'EmployeeTimingsByProject'])->name('employee-timings-by-project');
    


    Route::get('/dev-workload',[ReportController::class, 'devWorkloadReport'])->name('dev-workload');
    Route::get('/pm-workload',[ReportController::class, 'pmWorkloadReport'])->name('pm-workload');



});



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
