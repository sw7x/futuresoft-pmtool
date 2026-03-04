<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TimesheetController extends Controller
{
    public function pendingTimesheetList(){
        return view('timesheets.pending-list');
    }
    public function approvedTimesheetList(){
        return view('timesheets.approved-list');
    }

    public function myPendingTimesheetList(){
        return view('timesheets.my-pending-list');
    }
    public function myApprovedTimesheetList(){
        return view('timesheets.my-approved-list');
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
