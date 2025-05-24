<?php

namespace Modules\Reporting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reporting\Models\Report;




class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create 10 fake products using the factory
        Report::factory()->count(20)->create();
    }
}
