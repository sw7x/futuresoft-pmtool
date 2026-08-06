<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

//use Modules\Project\Database\Seeders\ProjectModuleDatabaseSeeder;
//use Modules\Leave\Database\Seeders\LeaveModuleDatabaseSeeder;
//use Modules\Timesheet\Database\Seeders\TimesheetModuleDatabaseSeeder;
//use Modules\Employee\Database\Seeders\EmployeeModuleDatabaseSeeder;
//use Modules\Task\Database\Seeders\TaskModuleDatabaseSeeder;
//use Modules\Communicate\Database\Seeders\CommunicateModuleDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
            
        $this->call(RoleSeeder::class);
        $this->call(DefaultAccountsSeeder::class);
        $this->call(UserSeeder::class);
        

        $this->call(PermissionsTableSeeder::class);
                

        // Employee
        //$this->call(DesignationSeeder::class);
        //$this->call(AssignDesignationToUserSeeder::class);
        $this->call(\Modules\Employee\Database\Seeders\EmployeeModuleDatabaseSeeder::class);


        // Leave
        //$this->call(LeavesTableSeeder::class);
        $this->call(\Modules\Leave\Database\Seeders\LeaveModuleDatabaseSeeder::class);

        
        // Project
        //$this->call(ClientSeeder::class);
        //$this->call(ProjectSeeder::class);
        //$this->call(ProjectPhaseSeeder::class);
        //$this->call(DeveloperProjectEnrollmentSeeder::class);
        //$this->call(InvoiceSeeder::class);
        $this->call(\Modules\Project\Database\Seeders\ProjectModuleDatabaseSeeder::class);


        // Task
        //$this->call(TaskSeeder::class);
        //$this->call(DeveloperTaskAssignmentSeeder::class);
        $this->call(\Modules\Task\Database\Seeders\TaskModuleDatabaseSeeder::class);


        // Communicate
        //$this->call(ProjectThreadSeeder::class);        
        //$this->call(PrivateMessageSeeder::class);
        //$this->call(TaskAssignmentMessageSeeder::class);
        //$this->call(TaskThreadSeeder::class);
        $this->call(\Modules\Communicate\Database\Seeders\CommunicateModuleDatabaseSeeder::class);


        // Timesheet
        //$this->call(TimesheetSeeder::class);
        $this->call(\Modules\Timesheet\Database\Seeders\TimesheetModuleDatabaseSeeder::class);
    }
}
