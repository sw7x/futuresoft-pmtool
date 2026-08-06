<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    


    public function client(){
        return view('project-module::client.client-list');
    }    

    public function createClient(){
        return view('project-module::client.client-create');
    }    

    public function singleClient(){
        return view('project-module::client.client-single');
    }
    














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
    Client Summary
        Total Projects-23
        Finished Projects-11
        Pending Projects-12
    */


        
    //public function getAllProjectsByClient($clientId) {}
}
