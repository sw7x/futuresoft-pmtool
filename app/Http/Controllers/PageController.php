<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{

    
    public function profile(){
        return view('profile');    
    }
    
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

    




    public function users(){
        return view('users');
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







    public function desigProjectwiseTiming(){
        return view('desig-projectwise-timing');
    }

    public function devProjectwiseTiming(){
        return view('dev-projectwise-timing');
    }

    public function designationManage(){
        return view('designation-manage');
    }


}
