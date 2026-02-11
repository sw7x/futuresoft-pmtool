<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Domain\Services\ProjectCreator;
use Illuminate\Support\Facades\Validator;
use Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Session;
use DB;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProjectController extends Controller
{


   /************************************************/
    private $projectCreator;

    public function __construct()
    {

       // $this->projectCreator = new ProjectCreator();
    }




    public function index(){
        return view('project');
    }
    public function assignEmployees(){
        return view('project-assign');
    }

    public function thread(){
        return view('thread-project');
    }


    
}
