<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{

    
    
    
    public function login(){
       //return view('login');
       return view('auth.login-page');
    }

    public function page404(){
        return view('errors.404');    
    }

   

    public function mailbox(){
        return view('messages.mailbox');    
    }
    
    public function readMail(){
        return view('messages.read-mail');    
    }
   
    public function compose(){
        return view('messages.compose');
    }

    


    public function profile(){
        return view('users.user-profile');    
    }

    public function users(){
        return view('users.user-list');
    }

    public function createUsers(){
        return view('users.user-create');
    }    

    public function viewSingleUser(){
        return view('users.user-view');
    }




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

    





    public function designationManage(){
        return view('designations.manage-designations');
    }
    public function viewDesignations(){
        return view('designations.view-designations');
    }
    public function assignDesignations(){
        return view('designations.assign-designations');
    }

}
