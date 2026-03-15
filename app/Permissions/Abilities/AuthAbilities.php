<?php 

namespace App\Permissions\Abilities;

class AuthAbilities{

	const CHANGE_PASSWORD 		 = 'change_password';
	const LOGIN 				 = 'login';
	const LOGOUT 				 = 'logout';	



	public static function get(string $ability): string
	{
	    $key = strtoupper($ability);
	    $constant = static::class . '::' . $key;

	    if (!defined($constant)) {
	        logger()->warning("AuthAbilities: undefined ability '{$ability}' called");
	        return 'UNKNOWN_ABILITY';
	    }

	    return constant($constant);
	}
}