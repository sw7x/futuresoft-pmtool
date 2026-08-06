<?php
namespace Modules\Task\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Task\Database\Seeders\TaskSeeder;
use Modules\Task\Database\Seeders\DeveloperTaskAssignmentSeeder;

class TaskModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TaskSeeder::class);
        $this->call(DeveloperTaskAssignmentSeeder::class);
    }
}
