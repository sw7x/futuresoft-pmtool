<?php

namespace Modules\Reporting\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ReportController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('ReportController.store');
    }

    public function index()
    {
        dump('ReportController.index');
    }    

    public function edit()
    {
        dump('ReportController.edit');
    }
}
