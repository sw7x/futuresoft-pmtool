<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate as GateFacade;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

use App\Models\User as UserModel;
use App\Permissions\Traits\DbPermissionLoader;

//use Sentinel;
//use Illuminate\Foundation\Auth\User as Authenticatable;


class AuthServiceProvider extends ServiceProvider
{
    use DbPermissionLoader;

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    
    /**
     * Called before all other service providers are registered
     * This method is called before boot()
     * overriden register method, called the singleton rebinding in registerAccessGate     
     */
    public function register()
    {
        $this->registerAccessGate();
        //todo 
        //bind sentinet::getUser() to Auth::user()
    }

    
    /*
    *   overriding Laravel's default Gate binding with a custom one that uses Sentinel for user resolution: 
    *    
    *   This needs to happen in register() because:
    *       It's binding a core service (Gate) into the container
    *       It must be available before any other code tries to use the Gate
    *       Other service providers might depend on this binding in their boot() methods
    */
    protected function registerAccessGate()
    {
        //dd($this->app['sentinel']);
        $this->app->singleton(GateContract::class, function ($app) {
            //dd($this->app['sentinel']);
            return new Gate($app, function () use ($app) {
                
                //This checks if the Sentinel service is registered in the container, NOT if a user is logged in
                //$app['sentinel'] returns null when no user is logged in, which is perfectly valid
                if (!isset($app['sentinel'])) {
                    throw new \RuntimeException('Sentinel service not registered');
                }

                return $this->app['sentinel']->getUser();
            });
        });  

        //When someone requests 'gate' from the container, give them whatever is bound to GateContract::class"
        $this->app->alias(GateContract::class, 'gate');
    }



    /**
     * Boot any authentication / authorization services.
     * This method is called after all providers are registered
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        // Load all gates
        $this->loadCustomGates();
        $this->loadDatabasePermissionGates();

        /*
        GateFacade::define('is-admin', function(UserModel $user) {
            return $user->isAdmin();
        });
        */
    }



    private function loadCustomGates()
    {
        $gatesFolder = base_path('app/Permissions/Gates');        
        $gatesFile = $gatesFolder . '/auth-gates.php';        
        if (file_exists($gatesFile)) {
            require_once $gatesFile;
        }        
    }


    /**
     * Load database permissions as gates
     * Skip during console commands to avoid issues with migrations
     * 
     * @return void
     */
    private function loadDatabasePermissionGates()
    {
        // Skip during console commands to avoid issues with migrations
        if (!$this->app->runningInConsole()) {
            $this->registerPermissionGates();
        }
    }


    /**
     * method to check if Sentinel is properly configured
     */
    protected function isSentinelAvailable()
    {
        return isset($this->app['sentinel']);
    }
}