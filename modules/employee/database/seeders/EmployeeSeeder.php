<?php

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;

class EmployeeModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Each module's main seeder calls its own sub-seeders
        $this->call([
            //RoleSeeder::class,
            //UserSeeder::class,
        ]);
    }
}

/*
// Root DatabaseSeeder.php calls every module
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmployeeModuleDatabaseSeeder::class,
            LeaveModuleDatabaseSeeder::class,
        ]);
    }
}
*/