<?php

namespace Modules\Leave\Database\Seeders;///---------------

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LeaveSeeder::class);
    }
}
