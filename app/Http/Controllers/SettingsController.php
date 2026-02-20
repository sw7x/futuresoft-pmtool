<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
//use DB;



class SettingsController extends Controller
{
    public function loadGeneralPage(){        
        return view('settings.general-settings');
    }    

    public function loadAdvancedPage(){        
        return view('settings.advanced-settings');
    }
}
