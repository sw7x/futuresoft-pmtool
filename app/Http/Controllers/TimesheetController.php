<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TimesheetController extends Controller
{
    

    public function managerTimesheetList(){
        return view('timesheets.manager-timesheet-list');
    }

    
    public function myTimesheetList(){
        return view('timesheets.my-timesheet-list');
    }

    public function createTimesheet(){
        return view('timesheets.create');
    }

    public function viewTimesheet(){
        return view('timesheets.view');
    }    

    public function approveTimesheet(){
        return view('timesheets.approve');
    }
}







