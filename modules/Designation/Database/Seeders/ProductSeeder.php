<?php

namespace Modules\Designation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Designation\Models\Product;




class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Create 10 fake products using the factory
        Product::factory()->count(20)->create();
    }
}
