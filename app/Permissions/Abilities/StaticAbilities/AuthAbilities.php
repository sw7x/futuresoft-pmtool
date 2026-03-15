<?php 

namespace App\Permissions\Abilities\StaticAbilities;

use App\Permissions\Abilities\StaticAbilities\BaseStaticAbilitiesResolver;

class AuthAbilities extends BaseStaticAbilitiesResolver
{
	const CHANGE_PASSWORD 		 = 'change_password';
	const LOGIN 				 = 'login';
	const LOGOUT 				 = 'logout';	
}