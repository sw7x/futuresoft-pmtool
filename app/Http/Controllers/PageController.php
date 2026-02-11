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
        return view('mailbox');    
    }
    
    public function readMail(){
        return view('read-mail');    
    }    



   /************************************************/
    public function compose(){
        return view('compose');
    }

    public function users(){
        return view('users');
    }

    
    public function listTimesheet(){
        return view('timesheet-list');
    }

    public function submitTimesheet(){
        return view('timesheet-submit');
    }

    public function viewTimesheet(){
        return view('timesheet-view');
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
