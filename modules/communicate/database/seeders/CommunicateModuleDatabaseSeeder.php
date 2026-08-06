<?php
namespace Modules\Communicate\Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Communicate\Database\Seeders\ProjectThreadSeeder;
use Modules\Communicate\Database\Seeders\PrivateMessageSeeder;
use Modules\Communicate\Database\Seeders\TaskAssignmentMessageSeeder;
use Modules\Communicate\Database\Seeders\TaskThreadSeeder;

class CommunicateModuleDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProjectThreadSeeder::class);
        $this->call(PrivateMessageSeeder::class);
        $this->call(TaskAssignmentMessageSeeder::class);
        $this->call(TaskThreadSeeder::class);
    }
}
