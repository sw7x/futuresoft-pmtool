<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CostController extends Controller
{


   /************************************************/

//    public function index(){
//        return view('project');
//    }
//
//    public function assignEmployees(){
//        return view('assign-employees');
//    }

    public function invoices(){
        return view('invoice-list');
    }


}
