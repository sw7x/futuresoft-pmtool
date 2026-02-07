<?php
namespace App\Data;

class RoleEnum {
    const ADMIN             = 'admin';
    const OWNER             = 'owner';
    const MANAGER           = 'manager';
    const PROJECT_MANAGER   = 'project_manager';
    const DEVELOPER         = 'developer';

    public static function getRole($role) {
        switch ($role) {
            case self::ADMIN:
                return 'admin';
            case self::OWNER:
                return 'owner';
            case self::MANAGER:
                return 'manager';
            case self::PROJECT_MANAGER:
                return 'project_manager';
            case self::DEVELOPER:
                return 'developer';
            default:
                return 'Unknown';
        }
    }
}