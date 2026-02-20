<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ReportController extends Controller
{
    public function projectTimingsByDesignation(){
        return view('reports.project-timings-by-designation');
    }

    public function DesignationTimingsByProject(){
        return view('reports.designation-timings-by-project');
    }

    public function ProjectTimingsByEmployee(){
        return view('reports.project-timings-by-employee');
    }

    public function EmployeeTimingsByProject(){
        return view('reports.employee-timings-by-project');
    }
}
