<?php
namespace Modules\Leave\Providers;

use Illuminate\Support\ServiceProvider;



class LeaveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 1. Load Config
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'leave');

        // 2. Register Middleware
        $this->app['router']->aliasMiddleware('check.leave', \Modules\Leave\Http\Middleware\CheckLeave::class);

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'leave');

        // 3. Load Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/translations', 'leave');

        // 4. Load Routes
        //$this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        // TODO - Optional: if you register route bindings
        // Route::model('designation', Designation::class);


        // 5. Publish Assets 
        // to publish files inside into PROJECT_ROOT/public folder 
        // run - php artisan vendor:publish --tag=leave-assets --force
        $this->publishes([
            __DIR__.'/../../resources/js'       => public_path('modules/leave/js'),
            __DIR__.'/../../resources/css'      => public_path('modules/leave/css'),            
            __DIR__.'/../../resources/images'   => public_path('modules/leave/images'),            
        ], 'leave-assets');       

    }
    

}
