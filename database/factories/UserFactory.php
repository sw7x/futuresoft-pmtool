<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Hash;

use Faker\Generator as Faker;
use Cartalyst\Sentinel\Sentinel;

use App\Models\User as UserModel;

class UserFactory extends Factory
{
    
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserModel::class;



    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        return [            
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
        
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement([
                $this->model::GENDER_MALE,
                $this->model::GENDER_FEMALE,
                $this->model::GENDER_OTHER,
                $this->model::GENDER_MALE,
                $this->model::GENDER_FEMALE                    
            ]),

            'address' => $this->faker->address,
            'nic' => $this->faker->unique()->numerify('#########V'),
            'profile_pic' => $this->faker->imageUrl(200, 200, 'people', true, 'User'),

            'date_of_joined' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d H:i:s'),
            
            'epf_etf_details' => $this->faker->text(100),
            'edu_qualifications' => $this->faker->text(100),
            'skills' => implode(', ', $this->faker->words(5)),
            'date_of_birth' => $this->faker->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),

            'account_status' => $this->faker->boolean(90), // 90% chance true
            'employment_status' => $this->faker->randomElement(['pending', 'active', 'resigned', 'terminated']),
            //'permissions' => json_encode(['view_dashboard', 'edit_profile']), // Dummy permissions
                        

            'last_login' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}













