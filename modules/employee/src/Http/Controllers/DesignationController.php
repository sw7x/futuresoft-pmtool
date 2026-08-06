<?php
namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Employee\Models\Designation as DesignationModel; 
use App\Models\User; 
use Modules\Project\Models\Client as ClientModel;


class DesignationController extends Controller
{
    public function designationManage(){
        //$tt = User::role('project_manager')->inRandomOrder()->first()->id;
        


        $developers = User::whereHas('roles', function ($query) { $query->where('slug', 'developer');})
            ->where('employment_status', 'active')
            ->get(['id', 'date_of_joined']);

        dd($developers); 
        dd($developers->); 





       $tt = User::whereHas('roles', function ($query) { $query->where('slug', 'project_manager');})->pluck('id')->toArray();
       $tt1 = User::whereHas('roles', function ($query) { $query->where('slug', 'project_manager');})->inRandomOrder()->first()->id;

        dump($tt);
        dd($tt1);

        /*$appliers = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['project_manager', 'developer']);
        })->pluck('id')->toArray();*/




        $client = ClientModel::find(5);
        dump($client);
        dump($client->profile_image);
        dump($client->profile_image_url);
        dump($client->toArray());


        dd();




        // Get users by role
        $appliers = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['project_manager', 'developer']);
        })->pluck('id')->toArray();

        dd($appliers);

        dump(User::find(6));
        dump(User::find(6)->getFirstRoleName());
        dd();





        $rec = DesignationModel::find(12);
        dump($rec); 
        dump($rec->getParentDesignations()); //--------------

        dump(collect($rec->getSubordinateDesignations())->pluck('name')); 
        dump($rec->getAllUsersUnderHierarchy()); //-------------------------
        dump($rec->getRootParent()); 
        dump($rec->getFullHierarchyTree()); 

        dump('______________');
        $devDesig       =   DesignationModel::where('name', 'Developer')->first();        
        dump($devDesig);
        $rr = $devDesig->getFullHierarchyTree()->random(3);
        dump($rr);
        dump($rr->pluck('id')->toArray());




        return view('employee-module::designations.manage-designations');
    }
    public function viewDesignations(){
        return view('employee-module::designations.view-designations');
    }
    public function assignDesignations(){
        return view('employee-module::designations.assign-designations');
    }   
}
