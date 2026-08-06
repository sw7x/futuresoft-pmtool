<?php
namespace Modules\Leave\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Leave\Database\Seeders\LeavesTableSeeder;

class LeaveModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LeavesTableSeeder::class,
        ]);
    }
}