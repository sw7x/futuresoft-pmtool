<?php
namespace Modules\Employee\Database\Seeders;





use Illuminate\Database\Seeder;
//use App\Models\Designation;
use Modules\Employee\Models\Designation; 

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Helper function to create designation only if it doesn't exist
        $createIfNotExists = function ($attributes) {
            return Designation::firstOrCreate(
                ['name' => $attributes['name']],
                $attributes
            );
        };

        //===========================================================
        /*
        $owner = $createIfNotExists([
            'name' => 'Owner',
            'short_code' => 'OWNER',
            'description' => 'System owner with full access and control',
            'parent_id' => null,
            'status' => 'enable',
        ]);
        */
        
        //CEO (CEO) [Top Level / parent_id: null]
        $ceo = $createIfNotExists([
            'name' => 'CEO',
            'short_code' => 'CEO',
            'description' => 'Chief Executive Officer - Top management',
            'parent_id' => null,
            'status' => 'enable',
        ]);

        
        //===========================================================
        /*
        // Manager level designations
        
        General Manager (GM) [Top Level / parent_id: null]
        └── Senior Manager (SM)
            └── Manager (MANAGER)
        */

        $generalManager = $createIfNotExists([
            'name' => 'General Manager',
            'short_code' => 'GM',
            'description' => 'General Manager - Oversees all operations',
            'parent_id' => null,
            'status' => 'enable',
        ]);

        $seniorManager = $createIfNotExists([
            'name' => 'Senior Manager',
            'short_code' => 'SM',
            'description' => 'Senior Manager - Heads a major department',
            'parent_id' => $generalManager->id,
            'status' => 'enable',
        ]);

        $manager = $createIfNotExists([
            'name' => 'Manager',
            'short_code' => 'MANAGER',
            'description' => 'Department Manager - Manages team and resources',
            'parent_id' => $seniorManager->id,
            'status' => 'enable',
        ]);        


        //===========================================================
        /*
        // Project Management designations
        
        Senior Project Manager (SR-PM) [Top Level / parent_id: null]
        └── Project Manager (PM)
            └── Assistant Project Manager (ASST-PM)
                └── Business Analyst (BA)
        */

        $seniorProjectManager = $createIfNotExists([
            'name' => 'Senior Project Manager',
            'short_code' => 'SR-PM',
            'description' => 'Senior Project Manager - Manages multiple large projects',
            'parent_id' => null,
            'status' => 'enable',
        ]);

        $projectManager = $createIfNotExists([
            'name' => 'Project Manager',
            'short_code' => 'PM',
            'description' => 'Project Manager - Manages project delivery and team',
            'parent_id' => $seniorProjectManager->id,
            'status' => 'enable',
        ]);

        $assistantProjectManager = $createIfNotExists([
            'name' => 'Assistant Project Manager',
            'short_code' => 'ASST-PM',
            'description' => 'Assistant Project Manager - Supports PM in project management',
            'parent_id' => $projectManager->id,
            'status' => 'enable',
        ]);

        $businessAnalyst = $createIfNotExists([
            'name' => 'Business Analyst',
            'short_code' => 'BA',
            'description' => 'Business Analyst - Requirements gathering and analysis',
            'parent_id' => $assistantProjectManager->id,
            'status' => 'enable',
        ]);


        //===========================================================
        /*
        // Technical/Development designations

        Senior Software Architect (SR-SA) [Top Level / parent_id: null]
        └── Software Architect (SA)
            ├── Lead Developer (LEAD-DEV)
            │   ├── Senior Software Engineer (SSE)
            │   │   └── Software Engineer (SE)
            │   │       ├── Developer (DEV)
            │   │       └── Junior Software Engineer (JSE)
            │   │           └── Intern (INTERN)
            │   └── Quality Assurance Lead (QA-LEAD)
            │       └── Senior Quality Assurance (SR-QA)
            │           └── Quality Assurance (QA)
            └── DevOps Architect (DEVOPS-ARCH)
                └── Senior DevOps Engineer (SR-DEVOPS-ARCH)
                    └── DevOps Engineer (DEVOPS-ENG)
        */

        $seniorSoftwareArchitect = $createIfNotExists([
            'name' => 'Senior Software Architect',
            'short_code' => 'SR-SA',
            'description' => 'Senior Software Architect - Designs system architecture',
            'parent_id' => null,
            'status' => 'enable',
        ]);

        $softwareArchitect = $createIfNotExists([
            'name' => 'Software Architect',
            'short_code' => 'SA',
            'description' => 'Software Architect - System design and technical leadership',
            'parent_id' => $seniorSoftwareArchitect->id,
            'status' => 'enable',
        ]);

        $leadDeveloper = $createIfNotExists([
            'name' => 'Lead Developer',
            'short_code' => 'LEAD-DEV',
            'description' => 'Lead Developer - Technical team lead',
            'parent_id' => $softwareArchitect->id,
            'status' => 'enable',
        ]);

        $seniorSoftwareEngineer = $createIfNotExists([
            'name' => 'Senior Software Engineer',
            'short_code' => 'SSE',
            'description' => 'Senior Software Engineer - Experienced developer',
            'parent_id' => $leadDeveloper->id,
            'status' => 'enable',
        ]);

        $softwareEngineer = $createIfNotExists([
            'name' => 'Software Engineer',
            'short_code' => 'SE',
            'description' => 'Software Engineer - Core development role',
            'parent_id' => $seniorSoftwareEngineer->id,
            'status' => 'enable',
        ]);

        $developer = $createIfNotExists([
            'name' => 'Developer',
            'short_code' => 'DEV',
            'description' => 'Software Developer - Core application developer',
            'parent_id' => $softwareEngineer->id,
            'status' => 'enable',
        ]);

        $juniorSoftwareEngineer = $createIfNotExists([
            'name' => 'Junior Software Engineer',
            'short_code' => 'JSE',
            'description' => 'Junior Software Engineer - Entry level developer',
            'parent_id' => $softwareEngineer->id,
            'status' => 'enable',
        ]);

        $intern = $createIfNotExists([
            'name' => 'Intern',
            'short_code' => 'INTERN',
            'description' => 'Intern - Training position',
            'parent_id' => $juniorSoftwareEngineer->id,
            'status' => 'enable',
        ]);

        // DevOps designations
        $devOpsArchitect = $createIfNotExists([
            'name' => 'DevOps Architect',
            'short_code' => 'DEVOPS-ARCH',
            'description' => 'DevOps Architect - Infrastructure and deployment strategy',
            'parent_id' => $softwareArchitect->id,
            'status' => 'enable',
        ]);

        $seniorDevOpsEngineer = $createIfNotExists([
            'name' => 'Senior DevOps Engineer',
            'short_code' => 'SR-DEVOPS-ARCH',
            'description' => 'Senior DevOps Engineer - Advanced infrastructure management',
            'parent_id' => $devOpsArchitect->id,
            'status' => 'enable',
        ]);

        $devOpsEngineer = $createIfNotExists([
            'name' => 'DevOps Engineer',
            'short_code' => 'DEVOPS-ENG',
            'description' => 'DevOps Engineer - Infrastructure and deployment',
            'parent_id' => $seniorDevOpsEngineer->id,
            'status' => 'enable',
        ]);

        // Additional technical roles
        $qualityAssuranceLead = $createIfNotExists([
            'name' => 'Quality Assurance Lead',
            'short_code' => 'QA-LEAD',
            'description' => 'QA Lead - Leads quality assurance team',
            'parent_id' => $leadDeveloper->id,
            'status' => 'enable',
        ]);

        $seniorQualityAssurance = $createIfNotExists([
            'name' => 'Senior Quality Assurance',
            'short_code' => 'SR-QA',
            'description' => 'Senior QA - Advanced testing and quality control',
            'parent_id' => $qualityAssuranceLead->id,
            'status' => 'enable',
        ]);

        $qualityAssurance = $createIfNotExists([
            'name' => 'Quality Assurance',
            'short_code' => 'QA',
            'description' => 'QA Engineer - Software testing and quality',
            'parent_id' => $seniorQualityAssurance->id,
            'status' => 'enable',
        ]);

    }
}