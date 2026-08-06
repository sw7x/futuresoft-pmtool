<?php
namespace Modules\Project\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Project\Database\Seeders\ClientSeeder;
use Modules\Project\Database\Seeders\ProjectSeeder;
use Modules\Project\Database\Seeders\ProjectPhaseSeeder;
use Modules\Project\Database\Seeders\DeveloperProjectEnrollmentSeeder;
use Modules\Project\Database\Seeders\InvoiceSeeder;



class ProjectModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,
            ProjectSeeder::class,
            ProjectPhaseSeeder::class,
            DeveloperProjectEnrollmentSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}