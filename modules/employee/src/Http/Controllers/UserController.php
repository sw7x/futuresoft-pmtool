<?php
namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(){
        return view('employee-module::users.user-profile');    
    }

    public function users(){
        return view('employee-module::users.user-list');
    }

    public function createUsers(){
        return view('employee-module::users.user-create');
    }    

    public function viewSingleUser(){
        return view('employee-module::users.user-view');
    }
}
