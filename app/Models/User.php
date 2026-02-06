<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;


class User extends CartalystUser
//class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable, Authorizable,SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    

    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_OTHER  = 'other';

    protected $appends = ['is_activated'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [       
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'phone',
        'gender',
        'address', 
        'nic',
        'profile_pic',
        'date_of_joined',
        'hourly_rate',
        'monthly_salary',
        'epf_etf_details',
        'edu_qualifications',
        'skills',
        'date_of_birth',
        'account_status',
        'employment_status',
        'designation_id',
        //'permissions'
        //'last_login'
        //'created_at'
        //'updated_at'
    ];
    
    protected $loginNames = ['email', 'username'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        'account_status' => 'boolean',
    ];

    public static function boot(){
        parent::boot();        
        /*static::creating(function ($model) {
            $model->uuid = str_replace('-', '', Uuid::uuid4()->toString());
        });*/
    }


    protected static function booted(){
        /*static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.status', 1);
        });*/
    }





    public function getProfilePicAttribute($value){
        
        if($this->roles->isEmpty()){
            $imagePath = asset('images/default-profile-images/user.png');
        }else{
            if($value){           
                $imagePath = asset('storage/'.$value);
            }else{            
                $userRole = $this->getUserRoles()->first()->slug;                
                switch ($userRole) {
                    case "admin":
                        $imagePath = asset('images/default-profile-images/admin.png');
                    break;
                    case "owner":
                        $imagePath = asset('images/default-profile-images/owner.png');
                    break;
                    case "manager":
                        $imagePath  = asset('images/default-profile-images/manager.png');
                    break;
                    case "pm":
                        $imagePath = asset('images/default-profile-images/pm.png');
                    break;
                    case "dev":
                        $imagePath = asset('images/default-profile-images/dev.png');
                    break;
                    default:
                        $imagePath = asset('images/default-profile-images/user.png');
                }                    
            }
        }        
        //dd($this->getUserRoles());    
        return $imagePath;
    }




    public function getUserRoles(){

        //return $this->roles[0]->getRoleSlug();
        $userRoles = array();

        $userRoles = $this->roles->each(function($item, $key){

            //dd($item->getRoleSlug());
            return $item->getRoleSlug();
        });
        return $userRoles;
    }


    public function getIsActivatedAttribute(){
        return $this->isactivated();
    }

    public function isactivated(){
        if($this->activations->isEmpty())
            return null;
        else
            return ($this->activations->first()->completed);
    }

}
