<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role as RoleModel;
use Modules\Employee\Models\Designation;





class AssignDesignationToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all users and designations
        $userRecs = User::all();
        $designationRecs = Designation::all();

        // Safety check to ensure data exists before seeding pivot
        if ($userRecs->isEmpty() || $designationRecs->isEmpty()) {
            $this->command->warn('Users or Designations table is empty. Skipping DesignationUserSeeder.');
            return;
        }

        // assign CEO designation for Owner
        $owner1 = User::where('username', 'owner1')->first();
        $ceoDesig = Designation::where('name', 'CEO')->first();
        $owner1->designations()->sync([$ceoDesig->id]);


        $excludedUsernames = ['admin', 'owner1'];
        $users = User::whereNotIn('username', $excludedUsernames)->get();

        $managerDesig   =   Designation::where('name', 'Manager')->first();
        $pmDesig        =   Designation::where('name', 'Project Manager')->first();
        $devDesig       =   Designation::where('name', 'Developer')->first();

        $managerDesigTree   = $managerDesig->getFullHierarchyTree();
        $pmDesigTree        = $pmDesig->getFullHierarchyTree();
        $devDesigTree       = $devDesig->getFullHierarchyTree();

        foreach ($users as $user) {
            $userRole = $user->getFirstRoleName();
            $desiIdArr = [];
            $randomCount = collect([2, 2, 2, 1])->random();

            if($userRole == RoleModel::MANAGER){    
                $desiIdArr = $managerDesigTree->random($randomCount)->pluck('id')->toArray();
            }

            if($userRole == RoleModel::PROJECT_MANAGER){        
                $desiIdArr = $pmDesigTree->random($randomCount)->pluck('id')->toArray();
            }

            if($userRole == RoleModel::DEVELOPER){
                $desiIdArr = $devDesigTree->random($randomCount)->pluck('id')->toArray();
            }

            $user->designations()->sync($desiIdArr);
        }

        $this->command->info('Successfully seeded user designation relationships!');
        $this->command->newLine(); // Adds 1 blank line    
    }
}



