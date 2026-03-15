<?php 

namespace App\Permissions\Abilities;

use App\Permissions\Abilities\Contracts\StaticAbilities;

class AuthAbilities extends StaticAbilities
{
	const CHANGE_PASSWORD 		 = 'change_password';
	const LOGIN 				 = 'login';
	const LOGOUT 				 = 'logout';	
}