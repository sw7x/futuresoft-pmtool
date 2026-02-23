<?php
namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use App\Common\Utils\AlertDataUtil;

use App\Permissions\Abilities\AuthAbilities;
use App\Permissions\Traits\GateCheck;


class ChangePasswordController extends Controller
{
    use GateCheck;

    public function __construct(){

    }


    /*
    public function changePassword(Request $request) {
        $this->hasGateAllowed(AuthAbilities::CHANGE_PASSWORD);
        return view ('auth.form-change-password');
    }
    */


    public function postChangePassword(Request $request) {
        //$this->hasGateAllowed(AuthAbilities::CHANGE_PASSWORD);


        try{

            $validator = Validator::make($request->all(), [
                'password_old'          =>'required|min:6|max:12',
                'password_new'          =>'required|min:6|max:12',
            ],[]);

            if ($validator->fails()){
                $errors = $validator->errors(); // Get errors variable (MessageBag)
                $messages = $errors->all(); // Get all messages as simple array
                
                return response()->json([
                    'status'    => 'error',
                    'msg'       => $messages[0] // or $errors->first()
                ], 422);                
            }

            $hasher         = Sentinel::getHasher();
            $oldPassword    = $request->password_old;
            $password       = $request->password_new;
            

            $user = Sentinel::getUser();
            if(is_null($user)){
                return response()->json([
                    'status'    => 'error',
                    'msg'       => 'You need to login before change your password'
                ], 401);
            }

            if (!$hasher->check($oldPassword, $user->password)){
                return response()->json([
                    'status'    => 'error',
                    'msg'       => 'Current password is incorrect'
                ], 400); // Changed to 400 Bad Request
            }

            Sentinel::update($user, array('password' => $password));
            
            return response()->json([
                'status'    => 'success',
                'msg'       => 'Password successfully updated'
            ], 200);
           

        }catch(CustomException $e){
            return response()->json([
                'status'    => 'error',
                'msg'       => $e->getMessage()
            ], 400);

        }catch(\Exception $e){
            return response()->json([
                'status'    => 'error',
                'msg'       => 'Failed to change your password'
            ], 500);
        }
    }

}
