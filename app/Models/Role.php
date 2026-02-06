<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Role extends Model
{
    use HasFactory;
    
    const ADMIN             = 'admin';
    const OWNER             = 'owner';
    const MANAGER           = 'manager';
    const PROJECT_MANAGER   = 'project_manager';
    const DEVELOPER         = 'developer';


    protected $fillable = [
        'uuid',
        'slug',
        'name',
        //permissions
    ];

    public static function boot(){
        parent::boot();        
        /*static::creating(function ($model) {
            $model->uuid = str_replace('-', '', Uuid::uuid4()->toString());
        });*/
    }

}