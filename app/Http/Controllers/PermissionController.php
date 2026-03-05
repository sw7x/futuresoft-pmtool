<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;




class PermissionController extends Controller
{
    public function loadPermissions(){
        return view('permissions');    
    }   

    

}
