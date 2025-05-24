<?php
namespace Modules\Reporting\Database\Factories;//-------------

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Reporting\Models\Report;



class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'  => $this->faker->word(),
            'desc' => $this->faker->word(), 
            'score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
