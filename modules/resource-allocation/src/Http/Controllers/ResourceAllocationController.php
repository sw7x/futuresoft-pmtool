<?php

namespace Modules\ResourceAllocation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ResourceAllocationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('ResourceAllocationController.store');
    }

    public function index()
    {
        dump('ResourceAllocationController.index');
    }    

    public function edit()
    {
        dump('ResourceAllocationController.edit');
    }
}
