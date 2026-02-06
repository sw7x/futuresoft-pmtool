<?php

namespace Database\Seeders;

use App\Models\User as UserModel;
use Sentinel;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;




class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            // create users folder          
            $userfolderPath     = storage_path('app/public/users');
                        
            $userSubFolders = [
                storage_path('app/public/users/managers'),
                storage_path('app/public/users/project_managers'),
                storage_path('app/public/users/developers')
            ];

            if (!File::exists($userfolderPath)) {
                File::makeDirectory($userfolderPath, 0777, true);
                $this->command->alert($userfolderPath.' - Folder created successfully.');
            }
                        
            foreach ($userSubFolders as $userFolder) {
                 if (!File::exists($userFolder)) {
                    File::makeDirectory($userFolder, 0777, true);
                    $this->command->alert($userFolder.' - Folder created successfully.');
                }
            }

            $users = UserModel::factory()->count(100)->make()->each(function ($userItem){
                $faker              = \Faker\Factory::create();
                $roleId             = $faker->randomElement([3,4,5]);
                $user               = $userItem->toArray();            
                $user['password']   = 'Pa$$w0rd!';
                       
                //TODO - fix image upload
                /*
                if($roleId == 3){
                    //MANAGERS

                    $user = array_merge($user,[
                        'monthly_salary'=> $faker->randomFloat(2, 10000, 1000000)
                    ]);
                    
                    $path           = storage_path('app/public/users/developers');
                    $profilePicSrc  = ('users/managers/' . $faker->image($path, 630, 820, 'manager-user', false, true));
                    $user = array_merge($user, array('profile_pic'=> $profilePicSrc));                
               
                }else if($roleId == 4){
                    //PROJECT MANAGERS

                    // TODO - set designation

                    $user = array_merge($user,[
                        'monthly_salary'=> $faker->randomFloat(2, 10000, 1000000)
                    ]);

                    $path           = storage_path('app/public/users/project_managers');
                    $profilePicSrc  = ('users/managers/' . $faker->image($path, 630, 820, 'project-manager-user', false, true));
                    $user = array_merge($user, array('profile_pic'=> $profilePicSrc));
                
                }else if($roleId == 5){
                    //DEVELOPERS

                    // TODO - set designation
                    
                    $user = array_merge($user,[
                        'hourly_rate'=> $faker->randomFloat(2, 100, 500)
                    ]);

                    
                    $path           = storage_path('app/public/users/developers');
                    $profilePicSrc  = ('users/managers/' . $faker->image($path, 630, 820, 'developer-user', false, true));
                    $user = array_merge($user, array('profile_pic'=> $profilePicSrc));

                }else{
                    $user = array_merge($user,array('profile_pic'=> null));
                }
                */            
                
                /*
                $path = storage_path('app/public/users/developers');
                $profilePicSrc = $faker->image($path, 630, 820, 'user', false, true);
                dump($profilePicSrc);
                dump(is_writable($path), $path);
                */


                //unset accessors
                unset($user['activations']);
                unset($user['is_activated']);
                unset($user['full_name']);
                unset($user['profile_pic']);              
                
                $user = Sentinel::registerAndActivate($user);
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            });

        } catch (\Exception $e) {
            $this->command->error('Failed to insert user records to database !');
            //dump($e->getMessage());
            //dump($e);
        }

    }
}
