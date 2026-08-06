<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    
    public function index() 
    {
        //service call
        /*
        Summary
            Total Tasks-45
            Pending Tasks-20
            Completed Tasks-25
        */
        return view('task-module::task-list');
    }

    public function createTask() 
    {
        return view('task-module::task.task-create');
    }

    public function storeTask(Request $request) {}









    public function store(Request $request)
    {
        dump('TaskManagementController.store');
    }

    
    public function edit()
    {
        dump('TaskManagementController.edit');
    }

    public function updateTask()
    {
        dump('updateTask');
    }

    public function deleteTask()
    {
        dump('deleteTask');
    }

    public function assignEmployees(){
        return view('task-module::task-assign');
    }
    

    public function taskView(){
        return view('task-module::task-view');
    }


    public function taskViewSingle($id){
        //dd($id);
        return view('task-module::task-view-single');
    }

    public function taskEditSingle($id){
        //dd($id);
        return view('task-module::task-edit-single');
    }




    public function singleTask($id) 
    {
        return view('task-module::task.task-single');
    }

    public function editTask($id) {}

    

    // Additional methods based on diagram/module
    
    // Assign task to employee
    // public function assignTask(Request $request, $taskId) {}

    // Add comment to task thread
    // public function addComment(Request $request, $taskId) {}
}



