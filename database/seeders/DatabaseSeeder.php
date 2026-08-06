<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        /*
        * seeders for modules
        */
        //$this->call(\Modules\Designation\Database\Seeders\DatabaseSeeder::class);
        //$this->call(\Modules\Reporting\Database\Seeders\DatabaseSeeder::class);
        
        $this->call(RoleSeeder::class);

        $this->call(DefaultAccountsSeeder::class);
        
        $this->call(UserSeeder::class);
        
        $this->call(PermissionsTableSeeder::class);
        
        $this->call(DesignationSeeder::class);


        $this->call(AssignDesignationToUserSeeder::class);

        $this->call(PrivateMessageSeeder::class);

        
        $this->call(LeavesTableSeeder::class);

        $this->call(ClientSeeder::class);
        
        // Project
        $this->call(ProjectSeeder::class);
        $this->call(ProjectPhaseSeeder::class);
        $this->call(DeveloperProjectEnrollmentSeeder::class);
        $this->call(ProjectThreadSeeder::class);
        $this->call(InvoiceSeeder::class);




        $this->call(TaskSeeder::class);
        $this->call(DeveloperTaskAssignmentSeeder::class);
        $this->call(TaskAssignmentMessageSeeder::class);
        $this->call(TaskThreadSeeder::class);


        $this->call(TimesheetSeeder::class);



    }
}
