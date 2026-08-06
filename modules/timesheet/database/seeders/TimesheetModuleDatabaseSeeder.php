<?php
namespace Modules\Timesheet\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Timesheet\Database\Seeders\TimesheetSeeder;

class TimesheetModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TimesheetSeeder::class,
        ]);
    }
}