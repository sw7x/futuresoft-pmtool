<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class TaskController extends Controller
{


   /************************************************/

//    public function index(){
//        return view('project');
//    }
//
//    public function assignEmployees(){
//        return view('assign-employees');
//    }

    public function thread($id){
        //dd($id);
        return view('threads.task-thread');
    }

    public function takThreadList(){
        return view('threads.task-thread-list');
    }

    






    public function assignEmployees(){
        return view('task-assign');
    }
    public function taskCreate(){
        return view('task-create');
    }

    public function taskView(){
        return view('task-view');
    }


}
