<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\UserInfoComposer;


use Illuminate\Support\Facades\View;



class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view::composer ([
            'includes.top-nav',
            'includes.side-nav',
        ], UserInfoComposer::class);


    }
}
