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
        $this->call(\Modules\Reporting\Database\Seeders\DatabaseSeeder::class);
        
        $this->call(RoleSeeder::class);

        $this->call(DefaultAccountsSeeder::class);
        
        $this->call(UserSeeder::class);
        
        $this->call(PermissionsTableSeeder::class);



    }
}
