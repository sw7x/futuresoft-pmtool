<?php

use App\Permissions\Abilities\AuthAbilities;
use Illuminate\Support\Facades\Gate as GateFacade;
use App\Models\User as UserModel;
use Illuminate\Auth\Access\Response;


GateFacade::define(
	AuthAbilities::CHANGE_PASSWORD, function(?UserModel $user) {
	
	return is_null($user) ? 
		Response::deny('You must first log in before changing your password. !') : 
		Response::allow(); 
});


GateFacade::define(
	AuthAbilities::LOGIN, function(?UserModel $user) {
	
	return is_null($user) ?
		Response::allow() : 
		Response::deny('You already logged in !');
});

GateFacade::define(
	AuthAbilities::LOGOUT, function(?UserModel $user) {
	
	return is_null($user) ? 
		Response::deny('Log in before you log out. !') : 
		Response::allow(); 
});



