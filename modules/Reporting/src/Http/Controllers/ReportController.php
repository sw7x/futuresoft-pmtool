<?php

namespace Modules\Reporting\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ReportController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    





    public function projectTimingsByDesignation(){
        return view('reporting-module::project-timings-by-designation');
    }

    public function DesignationTimingsByProject(){
        return view('reporting-module::designation-timings-by-project');
    }

    public function ProjectTimingsByEmployee(){
        return view('reporting-module::project-timings-by-employee');
    }

    public function EmployeeTimingsByProject(){
        return view('reporting-module::employee-timings-by-project');
    }

    public function devWorkloadReport(){
        return view('reporting-module::dev-workload');
    }

    public function pmWorkloadReport(){
        return view('reporting-module::pm-workload');
    }


}
