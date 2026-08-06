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
        return view('task.task-assign');
    }
    public function taskManage(){
        return view('task.task-manage');
    }

    public function taskView(){
        return view('task.task-view');
    }


    public function taskViewSingle($id){
        //dd($id);
        return view('task.task-view-single');
    }

    public function taskEditSingle($id){
        //dd($id);
        return view('task.task-edit-single');
    }

}
