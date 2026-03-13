<?php

namespace App\Common\SharedServices;

use App\Models\Role as RoleModel;

class RoleSharedService
{
    /**
     * Get role name by role ID
     * 
     * @param int|string $roleId
     * @return string|null
     */
    public static function getRoleNameById($roleId)
    {
        $roleArr = RoleModel::getAllRoleInfo();
        $key = array_search($roleId, array_column($roleArr, 'id'));
        
        if ($key !== false) {
            return $roleArr[$key]['name'];
        }

        return null;
    }

    /**
     * Get role ID by role name
     * 
     * @param string $roleName
     * @return int|string|null
     */
    public static function getRoleIdByName($roleName)
    {
        $roleArr = RoleModel::getAllRoleInfo();
        $key = array_search($roleName, array_column($roleArr, 'name'));
        
        if ($key !== false) {
            return $roleArr[$key]['id'];
        }

        return null;
    }
}
