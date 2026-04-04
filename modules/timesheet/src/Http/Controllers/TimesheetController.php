<?php

namespace Modules\Timesheet\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class TimesheetController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('TimesheetController.store');
    }

    public function index()
    {
        dump('TimesheetController.index');
    }    

    public function edit()
    {
        dump('TimesheetController.edit');
    }
}
