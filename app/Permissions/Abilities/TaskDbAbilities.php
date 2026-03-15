<?php 

namespace App\Permissions\Abilities;

use App\Permissions\Abilities\Contracts\DbAbilities;

class TaskDbAbilities extends DbAbilities
{
    // Abilities map - keys are the "constant names" users call statically
    protected const ABILITIES = [
        'PHP'           => '{{rolename}}_PHP',
        'EDIT'          => '{{rolename}}_EDIT',
        'VIEW'          => '{{rolename}}_VIEW',
        'DELETE'        => '{{rolename}}_DELETE',
        'CREATE'        => '{{rolename}}_CREATE',
        'ADMIN'         => '{{rolename}}_ADMIN',
        'PUBLISH'       => '{{rolename}}_PUBLISH',
        'APPROVE'       => '{{rolename}}_APPROVE',
        'EDIT_PROFILE'  => '{{rolename}}_EDIT_PROFILE',
    ];
}


/*
In your controllers, policies, or blade files:

1. SIMPLEST USAGE - Automatically gets current user's role from Sentinel
        Gate::allows(DbAbilities::PHP());                    // Returns 'ADMIN_PHP' if user is admin
        Gate::allows(DbAbilities::EDIT());                   // Returns 'ADMIN_EDIT' if user is admin
        Gate::allows(DbAbilities::VIEW());                   // Returns 'ADMIN_VIEW' if user is admin

2. For specific role (override automatic detection)
        Gate::allows(DbAbilities::forRole('manager')->PHP);  // Returns 'MANAGER_PHP'
        Gate::allows(DbAbilities::forRole('editor')->EDIT);  // Returns 'EDITOR_EDIT'

3. In blade templates
        @php $ability = DbAbilities::PHP() @endphp

        @can($ability)
            Show PHP content for current user's role
        @endcan

        @can(DbAbilities::forRole('admin')->EDIT)
            Show edit button for admin role
        @endcan

4. In policies
        public function update(User $user, Post $post)
        {
            return $user->hasAccess(DbAbilities::EDIT())
                ? Response::allow()
                : Response::deny('You cannot edit this post');
        }

5. With multiple abilities
        
        $abilities = [
            DbAbilities::PHP(),    ✔
            DbAbilities::EDIT(),   ✔
            DbAbilities::VIEW(),   ✔
        ];
        
        foreach ($abilities as $ability) {
            if (Gate::allows($ability)) {
                // Do something
            }
        }

*/