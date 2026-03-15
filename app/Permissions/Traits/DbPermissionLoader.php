<?php

namespace App\Permissions\Traits;

use Illuminate\Support\Facades\Gate;
use App\Models\Permission as PermissionModel;
use Illuminate\Support\Facades\Cache;


trait DbPermissionLoader
{
    /**
     * Load all permissions from database and register them as gates
     */
    protected function registerPermissionGates()
    {
        try {
            if ($this->permissionsTableExists()) {
                $permissions = $this->getPermissionsFromCache();
                foreach ($permissions as $permissionItem) {
                    $gateName = strtoupper($permissionItem['role']) .'_'. $permissionItem['key'];                    
                    Gate::define($gateName, function ($user) use ($permissionItem) {                        
                        return $this->checkUserPermission($user, $permissionItem);
                    });
                }
            }

        } catch (\Exception $e) {
            $this->logPermissionError($e);
        }
    }

    /**
     * Get permissions from cache or database
     */
    protected function getPermissionsFromCache()
    {
        /*
        TTL is kept as a safety fallback. Cache is normally cleared via model
        events when permissions change, but events may not fire if the DB is
        updated directly (SQL, seeders, migrations) or if a bug occurs.
        The TTL ensures stale cache eventually expires and rebuilds.
        */
        return Cache::remember(PermissionModel::CACHE_KEY, 3600, function () {
            
            return  PermissionModel::with('role')
                        ->where('status', true)
                        ->get()
                        ->map(function ($item) {
                            return [
                                'role'      =>  optional($item->role)->slug,
                                'key'       =>  $item->key,
                                'access'    =>  ($item->access==='allow')
                            ];
                        })
                        ->toArray();
        });
    }

    /**
     * Check if user has the required permission
     */
    protected function checkUserPermission($user, $permissionArr)
    {
        $roleName = $user->getFirstRoleName();
        return $roleName && 
            ($roleName == $permissionArr['role']) && 
            $permissionArr['access'];
    }

    /**
     * Check if permissions table exists
     */
    protected function permissionsTableExists()
    {
        try {
            return \Schema::hasTable('permissions');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Log permission loading errors
     */
    protected function logPermissionError(\Exception $e)
    {
        if (app()->environment('local')) {
            throw $e;
        }
        
        \Log::error('Failed to load permissions: ' . $e->getMessage());
    }
}