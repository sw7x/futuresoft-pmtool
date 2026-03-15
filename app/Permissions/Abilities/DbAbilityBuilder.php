<?php 

namespace App\Permissions\Abilities;

class DbAbilityBuilder
{
    protected string $roleName;

    public function __construct(string $roleName)
    {
        $this->roleName = strtoupper($roleName);
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
     * Resolve ability name against the abilities map in DbAbilities.
     */
    protected function resolve(string $name): string
    {
        $key = strtoupper($name);
        $map = DbAbilities::abilitiesMap();

        if (!array_key_exists($key, $map)) {
            logger()->warning("DbAbilityBuilder: undefined ability '{$name}' called for role '{$this->roleName}'");
            return 'UNKNOWN_ABILITY';
            //throw new \InvalidArgumentException("Undefined ability: {$name}");

        }

        return str_replace('{{rolename}}', $this->roleName, $map[$key]);
    }



}