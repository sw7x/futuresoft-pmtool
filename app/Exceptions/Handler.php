<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Sentinel;
use App\Models\Role as RoleModel;
use App\Exceptions\CustomException;
use App\Exceptions\InvalidUserTypeException;
use Illuminate\Auth\Access\AuthorizationException;

//use Illuminate\Foundation\Application;
//use App;
use Illuminate\Support\Facades\App;
use App\Common\Utils\AlertDataUtil;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception){
        
        $isGuest    = !Sentinel::check();        
        if(!$isGuest){
            $user            = Sentinel::getUser();
            $userRole        = optional($user->roles()->first())->name;   
            $allRoles        = RoleModel::getAllRoleNames();
            $invalidUserRole = !in_array($userRole, $allRoles);
        }       
        
        
        // for http exceptions (when use abort helper)
        if ($this->isHttpException($exception)){

            $statuCode  =   $exception->getStatusCode();
            if ($exception->getStatusCode() == 401){
                //$errorPage  =   'errors.401';
                return redirect()->route('auth.login')
                    ->with(AlertDataUtil::error('Authentication is required',[
                        'message2' => 'The page you are trying to access is inaccessible to unauthenticated users.'
                    ]));
            }
                

            if ($exception->getStatusCode() == 403)
                $errorPage  =   'errors.403';                
            
            if ($exception->getStatusCode() == 404)
                $errorPage  =   'errors.404';

            if ($exception->getStatusCode() == 419)
                $errorPage  =   'errors.419';

            if ($exception->getStatusCode() == 500)
                $errorPage  =   'errors.500';
                        
            $errMsg  =  $exception->getMessage() ?? '';
            
            if (isset($errorPage)) {
                // if  ajax request
                if ($request->ajax() || $request->wantsJson())
                    return response()->json([], $statuCode);                   
                
                return response()->view($errorPage, ['errMsg' => $errMsg], $statuCode);    
            }
        }

        
        // for CustomException
        if ($exception instanceof CustomException){            
            $errorPage  =   'errors.custom-exception';
            $msg        =   $exception->getMessage() ?? '';           
            return response()->view($errorPage, ['errMsg' => $msg]);                 
        }        

        
        // for InvalidUserTypeException
        if ($exception instanceof InvalidUserTypeException){            
            $errorPage  =   'errors.invalid-user-type-exception';
            $msg        =   $exception->getMessage() ?? '';            
            return response()->view($errorPage, ['errMsg' => $msg]);                
        }        


        // for AuthorizationException
        if ($exception instanceof AuthorizationException){            
            $errorPage  =   'errors.403';
            $msg        =   $exception->getMessage() ?? '';
            return response()->view($errorPage, ['errMsg' => $msg]);                 
        }
        

        // for \Exception and \Error
        if ($exception instanceof \Exception || $exception instanceof \Error){
            if((config('app.debug') != true) || App::environment('production')){                
                $errorPage  =   'errors.error';
                $msg        =   'Something went wrong';
                //$msg      =   $exception->getMessage() ?? '';
                return response()->view($errorPage, ['errMsg' => $msg]);
            }
        }        

        return parent::render($request, $exception);
    }





}
