<?php
namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Employee\Database\Seeders\AssignDesignationToUserSeeder;
use Modules\Employee\Database\Seeders\DesignationSeeder;

class EmployeeModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DesignationSeeder::class,
            AssignDesignationToUserSeeder::class,
        ]);
    }
}

