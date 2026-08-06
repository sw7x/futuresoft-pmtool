<?php

namespace Modules\Reporting\Http\Controllers;

use App\Http\Controllers\Controller;

//use App\Permissions\Abilities\StaticAbilities\AuthAbilities;
//use App\Permissions\Traits\PermissionCheck;



class DashboardController extends Controller
{
    //use PermissionCheck;

    public function index(){
        //$this->hasPermission(AuthAbilities::CHANGE_PASSWORD);
        return view('reporting-module::dashboard');
    }

    

}
