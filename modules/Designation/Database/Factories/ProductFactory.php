<?php
namespace Modules\Designation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Designation\Models\Product;



class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'  => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 1, 999), // Price between 1.00 and 999.00
            'stock' => $this->faker->numberBetween(0, 100),  // Stock quantity
        ];
    }
}
