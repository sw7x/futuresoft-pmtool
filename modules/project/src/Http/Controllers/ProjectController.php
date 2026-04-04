<?php

namespace Modules\Project\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ProjectController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('ProjectController.store');
    }

    public function index()
    {
        dump('ProjectController.index');
    }    

    public function edit()
    {
        dump('ProjectController.edit');
    }
}
