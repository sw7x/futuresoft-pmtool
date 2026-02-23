<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

use App\Permissions\Abilities\AuthAbilities;
use App\Permissions\Traits\PermissionCheck;



class PageController extends Controller
{
    use PermissionCheck;

    public function index(){
        $this->hasPermission(AuthAbilities::CHANGE_PASSWORD);
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
