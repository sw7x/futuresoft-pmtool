<?php
namespace Modules\Leave\Database\Factories;//-------------

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Reporting\Models\Leave;



class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'leave_name'  => $this->faker->word(),
            'desc' => $this->faker->word(), 
        ];
    }
}
