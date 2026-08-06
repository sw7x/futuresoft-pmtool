<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\Models\Client as ClientModel;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Project\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientTypes = ['initial', 'company'];
        $statuses = ['enable', 'disable'];
        $countries = ['USA', 'UK', 'Canada', 'Australia', 'Germany', 'France', 'Japan', 'Sri Lanka', 'India', 'Singapore'];
        
        return [
            'name' => $this->faker->name(),
            'company_name' => $this->faker->optional(0.7)->company(),
            'client_type' => $this->faker->randomElement($clientTypes),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->optional()->address(),
            'country' => $this->faker->optional(0.8)->randomElement($countries),
            'description' => $this->faker->optional()->paragraph(),
            'comments' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
            'profile_image' => null,
            //'created_at' => now(),
            //'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the client is active.
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'enable',
        ]);
    }

    /**
     * Indicate that the client is inactive.
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disable',
        ]);
    }

    /**
     * Indicate that the client is a company.
     */
    public function company()
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'company',
            'company_name' => $this->faker->company(),
        ]);
    }

    /**
     * Indicate that the client is an individual.
     */
    public function initial()
    {
        return $this->state(fn (array $attributes) => [
            'client_type' => 'initial',
            'company_name' => null,
        ]);
    }

    /**
     * Indicate that the client is from a specific country.
     */
    public function fromCountry(string $country)
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
        ]);
    }

    /**
     * Configure the model factory with specific client data.
     */
    public function configure()
    {
        return $this->afterMaking(function (ClientModel $client) {
            // After making the model instance
        })->afterCreating(function (ClientModel $client) {
            // After creating the model in database
        });
    }
}





/*

for this clients table

Schema::create('clients', function (Blueprint $table) {
    $table->id();
    
    // Client Information
    $table->string('name');
    $table->string('company_name')->nullable();
    $table->enum('client_type', ['initial', 'company'])->default('initial');
    
    // Contact & Location
    $table->string('email');
    $table->string('phone');
    $table->text('address')->nullable();
    $table->string('country')->nullable();
    
    // Additional Information
    $table->text('description')->nullable();
    $table->text('comments')->nullable();

    // Status
    $table->enum('status', ['enable', 'disable'])->default('enable');
        
    // Profile Picture
    $table->string('profile_image')->nullable(); // Store image path
                            
    // Timestamps
    $table->timestamps();
    
    // Soft Deletes (optional but recommended)
    $table->softDeletes();
});


*/