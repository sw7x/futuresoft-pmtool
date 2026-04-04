<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class UserManagementController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('UserManagementController.store');
    }

    public function index()
    {
        dump('UserManagementController.index');
    }    

    public function edit()
    {
        dump('UserManagementController.edit');
    }
}
