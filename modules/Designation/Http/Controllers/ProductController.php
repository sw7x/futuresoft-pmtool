<?php

namespace Modules\Designation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class ProductController extends Controller
{
    public function __construct()
    {
        //$this->middleware('check.stock')->only('store');
        $this->middleware('check.stock');
    }

    public function store(Request $request)
    {
        dump('product-store');
    }

    public function index()
    {
        dump('product-index');
    }    

    public function edit()
    {
        dump('product-edit');
    }
}
