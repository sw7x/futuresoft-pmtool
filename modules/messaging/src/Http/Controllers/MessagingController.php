<?php

namespace Modules\Messaging\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class MessagingController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        //$this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('MessagingController.store');
    }

    public function index()
    {
        dump('MessagingController.index');
    }    

    public function edit()
    {
        dump('MessagingController.edit');
    }
}
