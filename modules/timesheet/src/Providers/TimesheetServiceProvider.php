<?php

namespace Modules\Timesheet\Providers;

use Illuminate\Support\ServiceProvider;


 
class TimesheetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 1. Load Config
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'timesheet-module');

        // 2. Register Middleware
        //$this->app['router']->aliasMiddleware('check.report', \Modules\Reporting\Http\Middleware\CheckReport::class);

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'timesheet-module');

        // 3. Load Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/translations', 'timesheet-module');

        // 4. Load Routes
        //$this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        // TODO - Optional: if you register route bindings
        // Route::model('designation', Designation::class);


        // 5. Publish Assets 
        // to publish files inside into PROJECT_ROOT/public folder 
        // run - php artisan vendor:publish --tag=timesheet-module-assets --force
        $this->publishes([
            __DIR__.'/../../resources/js'       => public_path('modules/timesheet-module/js'),
            __DIR__.'/../../resources/css'      => public_path('modules/timesheet-module/css'),            
            __DIR__.'/../../resources/images'   => public_path('modules/timesheet-module/images'),            
        ], 'timesheet-module-assets');       

    }

}
