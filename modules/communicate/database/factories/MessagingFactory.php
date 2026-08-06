<?php
namespace Modules\Messaging\Database\Factories;//-------------

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Messaging\Models\Message;



class TimesheetFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
        ];
    }
}
