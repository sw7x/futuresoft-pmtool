<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sentinel;





class DefaultAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = Sentinel::getUserRepository();
        
        try {         
        
            $admin_credentials = [
                'email'    => 'admin@futuresoft.lk',
                'username' => 'admin',
            ];
            
            // Check if exists
            if (!$userRepository->findByCredentials($admin_credentials)) {
                $admin = [
                    'email'             => 'admin@futuresoft.lk',
                    'username'          => 'admin',
                    'password'          =>  env('APP_DEFAULT_ADMIN_PASS', 'abc123'),
                    'phone'             => '',                
                    'gender'            => 'male',
                    'nic'               => 'admin-nic',
                    'date_of_joined'   => date('Y-m-d H:i:s'),

                    'account_status'    =>  true,
                    'employment_status' => 'active',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
                
                $user_admin = Sentinel::registerAndActivate($admin);
                $role_admin = Sentinel::findRoleById(1);
                $role_admin->users()->attach($user_admin);
                
                $this->command->info('admin user created.');
            } else {
                $this->command->info('admin user already exists.');
            }

        } catch (\Exception $e) {
            //dump($e->getMessage());
            $this->command->error('Failed to seed default Admin record (username = admin) to database !');
        }



        try {                        
            
            $owner_credentials = [
                'email'    => 'owner1@futuresoft.lk',
                'username' => 'owner1',
            ];

            // Check if exists
            if (!$userRepository->findByCredentials($owner_credentials)) {
                $owner = [
                    'email'             => 'owner1@futuresoft.lk',
                    'username'          => 'owner1',
                    'password'          =>  env('APP_DEFAULT_OWNER_PASS', 'Pa$$w0rd!'),
                    'phone'             => '',                
                    'gender'            => 'male',
                    'nic'               => 'owner1-nic',
                    'date_of_joined'   => date('Y-m-d H:i:s'),

                    'account_status'    =>  true,
                    'employment_status' => 'active',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),                
                ];
                
                $user_owner = Sentinel::registerAndActivate($owner);
                $role__owner = Sentinel::findRoleById(2);
                $role__owner->users()->attach($user_owner);
                
                $this->command->info('owner1 user created.');
            } else {
                $this->command->info('owner1 user already exists.');
            }            

        } catch (\Exception $e) {
            $this->command->error('Failed to seed default editor record (username = owner1) to database !');
        }



        try {                        
            
            $manager_credentials = [
                'email'    => 'manager1@futuresoft.lk',
                'username' => 'manager1',
            ];

            if (!$userRepository->findByCredentials($manager_credentials)) {
                $manager = [
                    'email'             => 'manager1@futuresoft.lk',
                    'username'          => 'manager1',
                    'password'          =>  env('APP_DEFAULT_MANAGER_PASSWORD', 'Pa$$w0rd!'),
                    'phone'             => '',                
                    'gender'            => 'male',
                    'nic'               => 'manager1-nic',
                    'date_of_joined'   => date('Y-m-d H:i:s'),

                    'account_status'    =>  true,
                    'employment_status' => 'active',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),                    
                ];

                $user_manager = Sentinel::registerAndActivate($manager);
                $role__manager = Sentinel::findRoleById(3);
                $role__manager->users()->attach($user_manager);
                
                $this->command->info('manager1 user created.');
            } else {
                $this->command->info('manager1 user already exists.');
            }

        } catch (\Exception $e) {
            $this->command->error('Failed to seed default editor record (username = manager1) to database !');
        }


        try {
            
            $proj_manager_credentials = [
                'email'    => 'pm1@futuresoft.lk',
                'username' => 'pm1',
            ];

            if (!$userRepository->findByCredentials($proj_manager_credentials)) {
                $proj_manager = [
                    'email'             => 'pm1@futuresoft.lk',
                    'username'          => 'pm1',
                    'password'          =>  env('APP_DEFAULT_PM_PASS', 'Pa$$w0rd!'),
                    'phone'             => '',                
                    'gender'            => 'male',
                    'nic'               => 'pm1-nic',
                    'date_of_joined'   => date('Y-m-d H:i:s'),
                    'monthly_salary'    => 100000.00,

                    'account_status'    =>  true,
                    'employment_status' => 'active',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];

                $user_proj_manager = Sentinel::registerAndActivate($proj_manager);
                $role_proj_manager = Sentinel::findRoleById(4);
                $role_proj_manager->users()->attach($user_proj_manager);

                $this->command->info('pm1 user created.');

            }else {
                $this->command->info('pm1 user already exists.');
            }           

        } catch (\Exception $e) {
            dump($e->getMessage());
            $this->command->error('Failed to seed default project manager record (username = pm1) to database !');
        }



        try {
                        
            $dev_credentials = [
                'email'    => 'dev1@futuresoft.lk',
                'username' => 'dev1',
            ];
            
            if (!$userRepository->findByCredentials($dev_credentials)) {
                $developer = [
                    'email'             => 'dev1@futuresoft.lk',
                    'username'          => 'dev1',
                    'password'          =>  env('APP_DEFAULT_DEV_PASS', 'Pa$$w0rd!'),
                    'phone'             => '',                
                    'gender'            => 'male',
                    'nic'               => 'dev1-nic',
                    'date_of_joined'   => date('Y-m-d H:i:s'),
                    'hourly_rate'       => 500.00,

                    'account_status'    =>  true,
                    'employment_status' => 'active',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
                
                $user_developer = Sentinel::registerAndActivate($developer);
                $role_developer = Sentinel::findRoleById(5);
                $role_developer->users()->attach($user_developer);

                $this->command->info('dev1 user created.');
            }else {
                $this->command->info('dev1 user already exists.');
            }            

        } catch (\Exception $e) {
            dump($e->getMessage());
            $this->command->error('Failed to seed default developer record (username = dev1) to database !');
        }

    }
}