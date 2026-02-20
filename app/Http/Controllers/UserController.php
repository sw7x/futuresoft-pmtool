<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
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
}
