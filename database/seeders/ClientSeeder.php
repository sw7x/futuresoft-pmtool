<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Project\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Database\Factories\ClientFactory; // Add this import


class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific clients with realistic data
        //$this->createSpecificClients();
        
        // Create random clients using factory
        $this->createRandomClients();
    }

    /**
     * Create specific clients with realistic data.
     */
    private function createSpecificClients(): void
    {
        $clients = [
            [
                'name' => 'John Smith',
                'company_name' => 'TechCorp Solutions',
                'client_type' => 'company',
                'email' => 'john.smith@techcorp.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Silicon Valley Blvd, San Francisco, CA 94105',
                'country' => 'USA',
                'description' => 'Leading technology solutions provider specializing in AI and cloud computing.',
                'comments' => 'Key client for enterprise projects',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Sarah Johnson',
                'company_name' => 'GreenEnergy Innovations',
                'client_type' => 'company',
                'email' => 'sarah.j@greenenergy.com',
                'phone' => '+1 (555) 987-6543',
                'address' => '456 Renewable Energy Park, Austin, TX 78701',
                'country' => 'USA',
                'description' => 'Pioneering renewable energy solutions and sustainable technologies.',
                'comments' => 'Strategic partnership for green projects',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Michael Chen',
                'company_name' => 'Global Finance Partners',
                'client_type' => 'company',
                'email' => 'mchen@gfp.com',
                'phone' => '+44 20 7946 0123',
                'address' => '789 Financial District, London, EC2N 4AY',
                'country' => 'UK',
                'description' => 'International financial consulting and investment firm.',
                'comments' => 'Premium client with high-value projects',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Emma Wilson',
                'company_name' => 'Healthcare Plus',
                'client_type' => 'company',
                'email' => 'emma.w@healthcareplus.com',
                'phone' => '+61 2 9876 5432',
                'address' => '321 Medical Centre, Sydney, NSW 2000',
                'country' => 'Australia',
                'description' => 'Healthcare service provider with focus on telemedicine.',
                'comments' => 'Expanding digital health initiatives',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'David Kumar',
                'company_name' => 'Innovate Labs',
                'client_type' => 'company',
                'email' => 'david.k@innovatelabs.com',
                'phone' => '+91 98765 43210',
                'address' => '456 Tech Park, Bangalore, Karnataka 560001',
                'country' => 'India',
                'description' => 'R&D lab focused on emerging technologies and innovation.',
                'comments' => 'Collaborative research projects',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Lisa Tanaka',
                'company_name' => 'Digital Marketing Pro',
                'client_type' => 'company',
                'email' => 'lisa.t@digitalpro.com',
                'phone' => '+81 3 1234 5678',
                'address' => '789 Shibuya, Tokyo, 150-0002',
                'country' => 'Japan',
                'description' => 'Digital marketing agency specializing in social media and SEO.',
                'comments' => 'Ongoing marketing campaigns',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Robert Brown',
                'company_name' => 'Real Estate Holdings',
                'client_type' => 'company',
                'email' => 'rbrown@reholdings.com',
                'phone' => '+1 (555) 456-7890',
                'address' => '123 Commercial Avenue, New York, NY 10001',
                'country' => 'USA',
                'description' => 'Real estate development and property management company.',
                'comments' => 'Large-scale property projects',
                'status' => 'disable',
                'profile_image' => null,
            ],
            [
                'name' => 'Maria Garcia',
                'company_name' => 'Garcia Consulting',
                'client_type' => 'company',
                'email' => 'maria.g@garciaconsulting.com',
                'phone' => '+34 91 234 5678',
                'address' => '456 Paseo de la Castellana, Madrid, 28046',
                'country' => 'Spain',
                'description' => 'Management consulting firm specializing in digital transformation.',
                'comments' => 'Strategic consulting projects',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'James Anderson',
                'company_name' => 'EduTech Solutions',
                'client_type' => 'company',
                'email' => 'j.anderson@edutech.com',
                'phone' => '+44 20 7946 0987',
                'address' => '789 Education Campus, Manchester, M1 2AB',
                'country' => 'UK',
                'description' => 'Educational technology solutions provider.',
                'comments' => 'E-learning platform development',
                'status' => 'enable',
                'profile_image' => null,
            ],
            [
                'name' => 'Priya Patel',
                'company_name' => 'Priya\'s Consulting',
                'client_type' => 'initial',
                'email' => 'priya.patel@email.com',
                'phone' => '+91 98765 98765',
                'address' => null,
                'country' => 'India',
                'description' => 'Independent business consultant specializing in startups.',
                'comments' => 'Startup advisory services',
                'status' => 'enable',
                'profile_image' => null,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        $this->command->info('Created ' . count($clients) . ' specific clients.');        
        $this->command->newLine();
    }

    /**
     * Create random clients using the factory.
     */
    private function createRandomClients(): void
    {
        // Create 20 active company clients
        ClientFactory::new()
            ->count(20)
            ->company()
            ->active()
            ->create();

        $this->command->info('Created 20 active company clients.');

        // Create 15 active individual clients
        ClientFactory::new()
            ->count(15)
            ->initial()
            ->active()
            ->create();

        $this->command->info('Created 15 active individual clients.');

        // Create 5 inactive clients
        ClientFactory::new()
            ->count(5)
            ->inactive()
            ->create();

        $this->command->info('Created 5 inactive clients.');

        // Create clients from specific countries
        $countries = ['USA', 'UK', 'Canada', 'Australia', 'Germany', 'France', 'Japan'];
        foreach ($countries as $country) {
            ClientFactory::new()
                ->count(3)
                ->fromCountry($country)
                ->create();
        }

        $this->command->info('Created clients from various countries.');
        $this->command->newLine();
    }

    /**
     * Create clients with specific status distribution.
     */
    public function createWithStatusDistribution(): void
    {
        // 70% active, 30% inactive
        $total = 100;
        $activeCount = (int) ($total * 0.7);
        $inactiveCount = $total - $activeCount;

        ClientFactory::new()
            ->count($activeCount)
            ->active()
            ->create();

        ClientFactory::new()
            ->count($inactiveCount)
            ->inactive()
            ->create();

        $this->command->info("Created {$activeCount} active and {$inactiveCount} inactive clients.");
        $this->command->newLine();

    }

    /**
     * Create clients for development/testing purposes.
     */
    public function createDevelopmentClients(): void
    {
        $devClients = [
            [
                'name' => 'Dev Client 1',
                'company_name' => 'Dev Corp',
                'client_type' => 'company',
                'email' => 'dev1@test.com',
                'phone' => '1234567890',
                'address' => '123 Test Street',
                'country' => 'USA',
                'status' => 'enable',
            ],
            [
                'name' => 'Dev Client 2',
                'company_name' => 'Test Solutions',
                'client_type' => 'company',
                'email' => 'dev2@test.com',
                'phone' => '0987654321',
                'address' => '456 Demo Avenue',
                'country' => 'UK',
                'status' => 'enable',
            ],
            [
                'name' => 'Individual Tester',
                'company_name' => null,
                'client_type' => 'initial',
                'email' => 'tester@test.com',
                'phone' => '5555555555',
                'address' => '789 Sample Road',
                'country' => 'Canada',
                'status' => 'enable',
            ],
        ];

        foreach ($devClients as $client) {
            Client::create($client);
        }

        $this->command->info('Created development clients.');
        $this->command->newLine();

    }
}


// Use the factory directly
// $clients = ClientFactory::new()->count(10)->create();

// Or use the model with factory
// $clients = Client::factory()->count(10)->create();