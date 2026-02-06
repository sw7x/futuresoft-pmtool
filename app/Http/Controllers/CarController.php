<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;




class CarController extends Controller
{
    public function index(){
        dump(User::all()->toArray());


        User::all()->each(function ($user, $key) {
            dump($user->username .' - '. $user->roles->first()->slug.' - '. $user->profile_pic);
        });

        dd("CarController.index");

    }
}