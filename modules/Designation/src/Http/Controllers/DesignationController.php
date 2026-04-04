<?php

namespace Modules\Designation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class DesignationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('DesignationController.store');
    }

    public function index()
    {
        dump('DesignationController.index');
    }    

    public function edit()
    {
        dump('DesignationController.edit');
    }
}
