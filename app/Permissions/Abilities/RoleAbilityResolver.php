<?php 

namespace App\Permissions\Abilities;

class RoleAbilityResolver
{
    protected string $roleName;
    protected string $callerClass;

    public function __construct(string $roleName, string $callerClass)
    {
        $this->roleName = strtoupper($roleName);
        $this->callerClass = $callerClass;
    }

    /**
     * Property-style access: DbAbilities::forRole('admin')->PHP
     */
    public function __get(string $name): string
    {
        return $this->resolve($name);
    }

    /**
     * Method-style access: DbAbilities::forRole('admin')->PHP()
     */
    public function __call(string $name, array $arguments): string
    {
        return $this->resolve($name);
    }

    /**
     * Resolve ability name against the abilities map dynamically.
     */
    protected function resolve(string $name): string
    {
        $key = strtoupper($name);
        $map = call_user_func([$this->callerClass, 'abilitiesMap']);

        if (!array_key_exists($key, $map)) {
            $shortName = class_basename($this->callerClass);
            logger()->warning("{$shortName}Builder: undefined ability '{$name}' called for role '{$this->roleName}'");
            return 'UNKNOWN_ABILITY';
        }

        return str_replace('{{rolename}}', $this->roleName, $map[$key]);
    }
}
