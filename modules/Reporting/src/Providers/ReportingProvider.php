<?php

namespace Modules\Reporting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Designation\Http\Middleware\CheckStock;



class ReportingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 1. Load Config
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'reporting');

        // 2. Register Middleware
        $this->app['router']->aliasMiddleware('check.report', \Modules\Reporting\Http\Middleware\CheckReport::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
    */
    public function boot()
    {
        // 1. Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        
        // 2. Load Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'reporting');

        // 3. Load Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/translations', 'reporting');

        // 4. Load Routes
        //$this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        // Optional: if you register route bindings
        // Route::model('designation', Designation::class);
    }

    /*
    public function boot():void
    {        
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'designation');
        
        // Register route middleware
        //app('router')->aliasMiddleware('check.stock', CheckStock::class);
        $this->app['router']->aliasMiddleware('check.stock', CheckStock::class);

        //$this->app->regoster(DesignationRouteServiceProvider::class)
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'designation');
        $this->loadTranslationsFrom(__DIR__.'/../resources/translations', 'designation');
    }
    */

}
