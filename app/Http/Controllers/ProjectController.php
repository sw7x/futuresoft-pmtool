<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permissions\Traits\PermissionCheck;
use App\Models\Permission as PermissionModel;
use App\Permissions\Abilities\TaskDbAbilities;

/*
use App\Permissions\Abilities\AuthAbilities;
use Illuminate\Support\Facades\Cache;
use Sentinel;
use Illuminate\Support\Facades\Gate;
*/


class ProjectController extends Controller
{

    use PermissionCheck;

    public function __construct()
    {

    }


    public function index(){
        //dd(Sentinel::getUser()->getFirstRoleName());
        //$this->hasPermission(AuthAbilities::get('CHANGE_PASSWORD'));
        return view('projects.project-list');
    }    


    public function createProject(){

        $this->hasPermission(TaskDbAbilities::get('EDIT_PROFILE'));
        return view('projects.project-create');

        /*
        //dump(AuthAbilities::get('CHANGE_PASSWORD'));dd();
        $value = Cache::get('db_permissions_for_gates');
        dd($value);


        $gates = Gate::abilities();
        dd($gates);


        Cache::forget('db_permissions_for_gates');


        dump(DbAbilities::get('cPHP'));
        dump(DbAbilities::forRole('manager')->gPHP());
        dump(DbAbilities::get('sEDIT', 'manager'));
        dump(DbAbilities::get('PHP'));
        dd('_');
        */

    }   


    public function singleProject($id){
        return view('projects.project-single');
    }


    public function assignEmployees(){
        return view('projects.project-assign');
    }    
    public function viewTimeline(){
        return view('projects.project-timeline');
    }

    public function thread($id){
        //dd($id);
        return view('threads.project-thread');
    }

    public function projectThreadList(){
        return view('threads.project-thread-list');
    }    


    public function createThread(){
        return view('threads.create-thread');
    }
    
}