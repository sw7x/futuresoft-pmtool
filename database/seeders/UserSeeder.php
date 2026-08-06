<?php

namespace Database\Seeders;

use App\Models\User as UserModel;
use Sentinel;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;
//use Illuminate\Support\Str;
use App\Traits\GeneratesPlaceholderImages;




class UserSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

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
                storage_path('app/public/users/project-managers'),
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
                                      
                // TODO: uncomment image generation

                if ($roleId == 3) {
                    //MANAGERS
                    $user = array_merge($user, [
                        'monthly_salary' => $faker->randomFloat(2, 10000, 1000000),
                        /*'profile_pic'    => $this->makePlaceholderImage(
                            'users/managers', 630, 820, 'Manager', ['type' => 'face']
                        ),*/
                    ]);
 
                } else if ($roleId == 4) {
                    //PROJECT MANAGERS
                    $user = array_merge($user, [
                        'monthly_salary' => $faker->randomFloat(2, 10000, 1000000),
                        /*'profile_pic'    => $this->makePlaceholderImage(
                            'users/project-managers', 630, 820, 'PM', ['type' => 'face']
                        ),*/
                    ]);
 
                } else if ($roleId == 5) {
                    //DEVELOPERS
                    $user = array_merge($user, [
                        'hourly_rate' => $faker->randomFloat(2, 100, 500),
                        /*'profile_pic' => $this->makePlaceholderImage(
                            'users/developers', 630, 820, 'Dev', ['type' => 'face']
                        ),*/
                    ]);
 
                } else {
                    $user = array_merge($user, ['profile_pic' => null]);
                }

                //unset accessors
                unset($user['activations']);
                unset($user['is_activated']);                
                unset($user['full_name']);
                
                $user = Sentinel::registerAndActivate($user);
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            });

        } catch (\Exception $e) {
            $this->command->error('Failed to insert user records to database !');
            $this->command->error($e->getMessage());
            //dump($e->getMessage());
        }

    }

}