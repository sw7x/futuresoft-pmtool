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


    // Relationship with permissions
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }








    /**
     * Get all roles with only id, name, and slug
     */
    public static function getAllRoleInfo()
    {
        return self::select('id', 'name', 'slug')
            ->get()
            ->toArray();
    }
    
    /**
     * Get all roles as key-value pairs for dropdowns
     */
    public static function getRoleOptions()
    {
        return self::pluck('name', 'id')->toArray();
    }

    /**
     * Get all role names in a simple flat array
     */
    public static function getAllRoleNames()
    {
        return self::pluck('name')->toArray();
    }

    /**
     * Get all role slugs in a simple flat array (often matches the constants)
     */
    public static function getAllRoleSlugs()
    {
        return self::pluck('slug')->toArray();
    }
}