<?php

namespace Modules\Car\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class CarController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('CarController.store');
    }

    public function index()
    {
        dump('CarController.index');
    }    

    public function edit()
    {
        dump('CarController.edit');
    }
}
