<?php

namespace Modules\Project\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Project\View\Composers\UserInfoComposer;
use Illuminate\Support\Facades\View;
 
class ProjectViewServiceProvider extends ServiceProvider
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
        View::composer ([
            'project-module::project.project-assign',
            'project-module::project.project-list'
        ], UserInfoComposer::class);


    }
}
