<?php 

namespace App\Permissions\Abilities\StaticAbilities;

class BaseStaticAbilitiesResolver
{
    public static function get(string $ability): string
    {
        $key = strtoupper($ability);
        $constant = static::class . '::' . $key;
        $className = class_basename(static::class);

        if (!defined($constant)) {
            logger()->warning("{$className}: undefined ability '{$ability}' called");
            return 'UNKNOWN_ABILITY';
        }

        return constant($constant);
    }
}
