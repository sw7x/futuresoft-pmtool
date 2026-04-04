<?php

namespace Modules\ProjectProgressTracking\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ProjectProgressTrackingController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('ProjectProgressTrackingController.store');
    }

    public function index()
    {
        dump('ProjectProgressTrackingController.index');
    }    

    public function edit()
    {
        dump('ProjectProgressTrackingController.edit');
    }
}
