<?php

namespace Modules\Communicate\Http\Controllers;

use App\Http\Controllers\Controller;


class TaskThreadController extends Controller
{


   public function thread($id){
        //dd($id);
        return view('communicate-module::threads.task-thread');
    }

    public function takThreadList(){
        return view('communicate-module::threads.task-thread-list');
    }

    



}
