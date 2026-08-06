<?php
namespace Modules\Core\Http\Controllers;

//use Illuminate\Http\Request;
//use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class SettingsController extends Controller
{
    public function loadGeneralPage(){        
        return view('core-module::settings.general-settings');
    }    

    public function loadAdvancedPage(){        
        return view('core-module::settings.advanced-settings');
    }
}
