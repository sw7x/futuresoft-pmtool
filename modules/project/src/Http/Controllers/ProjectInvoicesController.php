<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectInvoicesController extends Controller
{
    



    public function invoices(){
        return view('project-module::invoice.invoice-list');
    }    

    public function createInvoice(){
        return view('project-module::invoice.invoice-create');
    }    

    public function singleInvoice($id){
        return view('project-module::invoice.invoice-single');
    }












//////////////////////////////



    public function index($projectId) {

        //service
        /*
        Summary
            Total Costs- $12,450
            Total Income    $18,000
            Profit- $5,550
        */




    }
    public function create($projectId) {}
    public function store(Request $request, $projectId) {}
    public function show($projectId, $id) {

        //service calls
        //Project-FutureSoft ERP Update
        //Client-Global Solutions Inc.


    }
    public function edit($projectId, $id) {}
    public function update(Request $request, $projectId, $id) {}
    public function destroy($projectId, $id) {}

    
    
    //Calculate Employee cost (Employee cost = employee hourly rate * time)
    //Calculate total Cost
    //deduct income amount from cost.(Calculating profit)

}
