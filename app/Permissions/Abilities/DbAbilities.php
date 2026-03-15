<?php 

namespace App\Permissions\Abilities;

use Sentinel;

class DbAbilities
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

    /**
     * Get the current authenticated user's role name (uppercased).
     * Returns null instead of throwing, so __callStatic can return a
     * distinct fallback string rather than crashing.
     */
    protected static function getCurrentRoleName(): ?string
    {
        if (!Sentinel::check()) {
            logger()->warning('DbAbilities: ability resolved while user is not logged in');
            //throw new \RuntimeException('No authenticated user');
            return null;
        }

        $user     = Sentinel::getUser();
        $roleName = $user->getFirstRoleName();

        if (empty($roleName)) {
            logger()->warning('DbAbilities: authenticated user has no role assigned', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        return strtoupper($roleName);
    }

    /**
     * Magic static call — resolves DbAbilities::PHP(), DbAbilities::EDIT(), etc.
     *
     * Usage:
     *   DbAbilities::PHP()          → 'ADMIN_PHP'          (role auto-detected)
     *   DbAbilities::PHP('editor')  → 'EDITOR_PHP'         (role explicitly passed)
     *   DbAbilities::FOOBAR()       → 'UNKNOWN_ABILITY'    (invalid ability key)
     *   DbAbilities::PHP() (guest)  → 'UNAUTHENTICATED_ABILITY' (not logged in)
     *   DbAbilities::PHP() (no role)→ 'UNASSIGNED_ABILITY' (logged in, no role)
     */
    public static function __callStatic(string $name, array $arguments): string
    {
        $key = strtoupper($name);

        // Invalid ability key
        if (!array_key_exists($key, static::ABILITIES)) {
            logger()->warning("DbAbilities: undefined ability '{$name}' called");
            return '___UNKNOWN_ABILITY___';
        }

        // Explicit role passed as first argument — use it directly
        if (!empty($arguments[0])) {
            $roleName = strtoupper($arguments[0]);
            return str_replace('{{rolename}}', $roleName, static::ABILITIES[$key]);
        }

        // Auto-detect role from current user
        $roleName = static::getCurrentRoleName();

        if ($roleName === null) {
            // Distinguish: not logged in vs logged in but no role
            return Sentinel::check() ? '___UNASSIGNED_ABILITY___' : '___UNAUTHENTICATED_ABILITY___';
        }

        return str_replace('{{rolename}}', $roleName, static::ABILITIES[$key]);
    }


    /**
     * Explicit helper — useful when IDE static analysis struggles with __callStatic.
     *
     * Usage:
     *   DbAbilities::get('PHP')            → 'ADMIN_PHP'
     *   DbAbilities::get('EDIT', 'manager') → 'MANAGER_EDIT'
     */
    public static function get(string $ability, ?string $roleName = null): string
    {
        return static::__callStatic($ability, $roleName ? [$roleName] : []);
    }

    /**
     * Return a builder locked to a specific role.
     *
     * Usage:
     *   DbAbilities::forRole('manager')->PHP   → 'MANAGER_PHP'
     *   DbAbilities::forRole('editor')->EDIT   → 'EDITOR_EDIT'
     */
    public static function forRole(string $roleName): DbAbilityBuilder
    {
        return new DbAbilityBuilder(strtoupper($roleName));
    }

    /**
     * Expose the raw abilities map so DbAbilityBuilder can reuse it.
     *
     * @return array<string, string>
     */
    public static function abilitiesMap(): array
    {
        return static::ABILITIES;
    }
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