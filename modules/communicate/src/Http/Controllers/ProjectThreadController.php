<?php

namespace Modules\Communicate\Http\Controllers;

use App\Http\Controllers\Controller;






class ProjectThreadController extends Controller
{


    public function __construct()
    {

    }

    

   
    

    public function thread($id){
        //dd($id);
        return view('communicate-module::threads.project-thread');
    }

    public function projectThreadList(){
        return view('communicate-module::threads.project-thread-list');
    }    


    public function createThread(){
        return view('communicate-module::threads.create-thread');
    }
    
}