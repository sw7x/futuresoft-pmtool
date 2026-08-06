<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MessageController extends Controller
{
    public function mailbox(){
        return view('messages.mailbox');    
    }
    
    public function readMail(){
        return view('messages.read-mail');    
    }
   
    public function compose(){
        return view('messages.compose');
    }
}
