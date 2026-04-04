<?php

namespace Modules\TaskManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class TaskManagementController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('TaskManagementController.store');
    }

    public function index()
    {
        dump('TaskManagementController.index');
    }    

    public function edit()
    {
        dump('TaskManagementController.edit');
    }
}
