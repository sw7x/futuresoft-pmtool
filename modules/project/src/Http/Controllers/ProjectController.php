<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    /*
    public function index() {}
    public function create() {}
    public function store(Request $request) {}
    public function show($id) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
    */


    //service call
    /*
        Assigned Employees(Developers)12
        Total Cost (Till Today)        $ 25000.00
        Total Income (Till Today)        $ 34500.00


        Managed by (PM)

    */


    public function index(){




        

        //dd(RoleModel::getAllRoleNames());
        //dd(Sentinel::getUser()->getFirstRoleName());
        //$this->hasPermission(AuthAbilities::get('CHANGE_PASSWORD'));
        return view('project-module::project.project-list');
    }    


    public function createProject(){

        //$this->hasPermission(TaskDbAbilities::get('EDIT_PROFILE'));
        return view('project-module::project.project-create');

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
        return view('project-module::project.project-single');
    }


    public function assignEmployees(){
        //dd('f');
        return view('project-module::project.project-assign');
    }    
    
    
    
    

    









}
