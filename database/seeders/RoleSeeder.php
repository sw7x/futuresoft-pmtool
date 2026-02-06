<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;



class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {                        
            
            $roles = [
                [
                    'id' => 1,
                    //'uuid'=> str_replace('-', '', Uuid::uuid4()->toString()),
                    'slug' => 'admin',
                    'name' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    //'uuid'=> str_replace('-', '', Uuid::uuid4()->toString()),
                    'slug' => 'owner',
                    'name' => 'owner',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 3,
                    //'uuid'=> str_replace('-', '', Uuid::uuid4()->toString()),
                    'slug' => 'manager',
                    'name' => 'manager',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    //'uuid'=> str_replace('-', '', Uuid::uuid4()->toString()),
                    'slug' => 'project_manager',
                    'name' => 'project_manager',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    //'uuid'=> str_replace('-', '', Uuid::uuid4()->toString()),
                    'slug' => 'developer',
                    'name' => 'developer',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]                
            ];


            // Check and insert only non-existing roles
            foreach ($roles as $role) {
                $exists = DB::table('roles')
                    ->where('id', $role['id'])
                    ->orWhere('slug', $role['slug'])
                    ->exists();
                
                if (!$exists) {
                    DB::table('roles')->insert($role);
                    $this->command->info("{$role['slug']} role added.");
                }else{
                    $this->command->info("{$role['slug']} role already exists.");
                }
            }            

        } catch (\Exception $e) {
            //dump($e->getMessage());
            $this->command->error('Failed to seed roles to database !');
        }   
    }
}
