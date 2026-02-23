<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;


class PageController extends Controller
{

    public function index(){
        return view('dashboard');
    }

    public function page404(){
        return view('errors.404');    
    }   

    public function index2(){
        dump(User::all()->toArray());


        User::all()->each(function ($user, $key) {
            dump($user->username .' - '. $user->roles->first()->slug.' - '. $user->profile_pic);
        });

        dd("PageController.index2");

    }

}
