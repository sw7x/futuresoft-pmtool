<?php

namespace Modules\Leave\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
 



class LeaveController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('LeaveController.store');
    }

    public function index()
    {
        dump('LeaveController.index');
    }    

    public function edit()
    {
        dump('LeaveController.edit');
    }


    public function config()
    {
        $value = Config::get('leave.max_days');
        $value1 = Config::get('leave.ll_txt');
        dd($value1);
    }
}
