<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DesignationController extends Controller
{
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
