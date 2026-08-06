<?php

namespace Modules\Communicate\Http\Controllers;

use App\Http\Controllers\Controller;


class MessageController extends Controller
{
    public function mailbox(){
        return view('communicate-module::messages.mailbox');    
    }
    
    public function readMail(){
        return view('communicate-module::messages.read-mail');    
    }
   
    public function compose(){
        return view('communicate-module::messages.compose');
    }
}
