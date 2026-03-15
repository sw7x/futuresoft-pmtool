<?php 

namespace App\Permissions\Abilities\Contracts;

use Sentinel;
use App\Permissions\Abilities\RoleAbilityResolver;


abstract class DbAbilities
{
    // Abilities map - override in child classes
    protected const ABILITIES = [];

    /**
     * Get the current authenticated user's role name (uppercased).
     * Returns null instead of throwing, so __callStatic can return a
     * distinct fallback string rather than crashing.
     */
    protected static function getCurrentRoleName(): ?string
    {
        if (!Sentinel::check()) {
            logger()->warning(class_basename(static::class) . ': ability resolved while user is not logged in');
            return null;
        }

        $user     = Sentinel::getUser();
        $roleName = $user->getFirstRoleName();

        if (empty($roleName)) {
            logger()->warning(class_basename(static::class) . ': authenticated user has no role assigned', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        return strtoupper($roleName);
    }

    /**
     * Magic static call — resolves capabilities dynamically.
     */
    public static function __callStatic(string $name, array $arguments): string
    {
        $key = strtoupper($name);
        $shortName = class_basename(static::class);

        // Invalid ability key
        if (!array_key_exists($key, static::ABILITIES)) {
            logger()->warning("{$shortName}: undefined ability '{$name}' called");
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
     */
    public static function get(string $ability, ?string $roleName = null): string
    {
        return static::__callStatic($ability, $roleName ? [$roleName] : []);
    }

    /**
     * Return a builder locked to a specific role.
     */
    public static function forRole(string $roleName): RoleAbilityResolver
    {
        return new RoleAbilityResolver(strtoupper($roleName), static::class);
    }

    /**
     * Expose the raw abilities map so RoleAbilityResolver can reuse it.
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