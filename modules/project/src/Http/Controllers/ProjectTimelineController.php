<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectTimelineController extends Controller
{
    
    public function viewTimeline(){
        return view('project-module::project.project-timeline');
    }








    /**
     * Display Project Timeline
     * Includes Scheduled Milestones and Actual Milestones
     */
    public function showTimeline($projectId)
    {
        //service
        /*
        Summary
            Scheduled Milestones-12
            Actual Milestones (Completed)-06
        */
        

    }

   //getPhaseDetails(projectId, phaseId)




    //phase mark complete for Actual Milestones of the project




}



