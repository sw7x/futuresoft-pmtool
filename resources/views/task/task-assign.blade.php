@extends('layouts.master',['title' => 'Task Assign'])
@section('title','Task Assign')


@section('css-files')
    <link rel="stylesheet" href="{{asset('plugins/jquery.timeline-2.1.3/dist/jquery.timeline.min.css')}}">

@stop

@section('page-css')
    <style>
        .dd-handle.dev-card {
            height: auto;
            padding: 2px 5px;
            background: #6b728005 !important;
            border: 1px solid #cbd5e0 !important;
            color: #2d3748;
            border-radius: 0px;
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: move;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 8px;
        }
        .dd-handle.dev-card:hover {
            background: #6b728015 !important;
            border-color: #9ca3af !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }
        .dev-avatar {
            width: 42px;
            height: 42px;
            background: #667eea;
            color: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
            margin-right: 14px;
            flex-shrink: 0;
        }
        .dev-info {
            flex-grow: 1;
            padding: 3px 5px;
        }
        .dev-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 0px;
            display: block;
            color: #1a202c;
        }
        .dev-role {
            font-size: 12px;
            color: #718096;
            display: inline-block;
            letter-spacing: -0.2px;
        }
        .dev-stats {
            margin-top: 2px;
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #6c757d;
        }
        .dev-stats i {
            margin-right: 7px;
            color: #a0aec0;
            font-size: 14px;
        }
        .btn-view-projects {
            color: #83898fd6 !important;
            font-size: 24px;
            text-decoration: none !important;
            transition: all 0.2s;
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-view-projects:hover {
            color: #4299e1 !important;
            transform: scale(1.1);
        }
        /* Fix for nestable handle text */
        .dd-handle {
            border: none;
        }
        /* Nestable customizations */
        .dd-item > button {
            margin-top: 22px;
            color: #718096;
        }
        .dd-list .dd-list {
            padding-left: 30px;
        }
        .dd-placeholder {
            background: #f7fafc;
            border: 1px dashed #cbd5e0;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        /* Modal styling for projects */
        .project-detail-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            transition: border-color 0.2s;
        }
        .project-detail-card:hover {
            border-color: #cbd5e0;
        }
        .project-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: block;
        }
        .project-info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .info-item {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #718096;
        }
        .info-item i {
            font-size: 14px;
            margin-right: 6px;
        }
        
         #nestable2 .dd-handle {
            padding: 3px 5px;
        }

        .info-item .icon-role { color: #4a5568; }
        .info-item .icon-date { color: #e53e3e; }
        .info-item .icon-status { color: #38a169; }




        /* Targeting the base popover class */
        .popover {
            border-radius: 1px;    
            font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        
        /* Specifically targeting the header and body if needed */
        .popover-header {
            /* font-weight: bold;*/
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* display: none;*/
        }

        .popover-body {
            font-size: 12px;
            color: #343A40;
        }

        /* Premium Info Box Styling */
        .proj-info {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1ab394;
            border-radius:0px;
            padding: 20px;
            margin-top: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: none; /* Hidden until data is loaded */
        }
        .proj-info .info-row {
            margin-bottom: 10px;
            display: flex;
            border-bottom: 1px solid #f7fafc;
            padding-bottom: 8px;
        }
        .proj-info .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .proj-info .label-text {
            font-weight: 700;
            color: #718096;
            min-width: 130px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .proj-info .value-text {
            color: #2d3748;
            font-size: 14px;
            font-weight: 500;
        }
        .proj-info .value-text#proj-time-period {
            color: #e53e3e;
            font-weight: 600;
        }
        .proj-info i {
            width: 20px;
            color: #a0aec0;
            margin-right: 8px;
        }

        .proj-info .proj-info-header {
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 20px;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .proj-info .proj-info-header h4 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .proj-info .proj-info-header i {
            color: #1ab394;
            font-size: 18px;
            margin-right: 12px;
            width: auto;
        }

        .proj-info .proj-info-close {
            margin-left: auto;
            background: none;
            border: none;
            color: #a0aec0;
            font-size: 20px;
            cursor: pointer;
            padding: 0 5px;
            line-height: 1;
            transition: color 0.2s;
        }
        .proj-info .proj-info-close:hover {
            color: #e53e3e;
        }




        /* Style for the present time marker line */
        .jqtl-present-time {
            border-left: 3px solid #ff0000 !important; /* Red line */
            opacity: 0.8;
            z-index: 100;
        }

        /* Style for the marker dot/indicator */
        .jqtl-present-time::before {
            /*
            content: '●';
            position: absolute;
            left: -8px;
            top: -10px;
            color: #ff0000;
            font-size: 16px;
            text-shadow: 0 0 5px rgba(255,0,0,0.5);
            */
            display: none;

        }

        /* Style for the marker label */
        .jqtl-present-time::after {
            content: 'Today';
            position: absolute;
            /* 
            left: -20px;
            top: -5px;
            */
            left: 0px;
            top: 0px;
            background: #ff0000;
            color: white;
            padding: 2px 2px;
            border-radius: 0px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
            width: 40px;
            /* display: block; */
            height: 15px;
        }








    </style>
@stop

@section('content')
    

    <div class="">
        
        
        <div class="row">
            <div class="col-lg-12">
                @if(Session::has('message'))
                    <x-flash-message  
                        :class="Session::get('cls', 'flash-info')"  
                        :title="Session::get('msgTitle') ?? 'Info!'" 
                        :message="Session::get('message') ?? ''"  
                        :message2="Session::get('message2') ?? ''"  
                        :canClose="true" />
                @endif
            </div>
        </div>

        <div class="ibox-content m-b-sm border-bottom">
            <h2 class="mb-4 font-bold text-muted">Select Project</h2>
            <div class="row">                 
                <div class="col-lg-4">               
                    <label for="project-select" class="font-weight-bold mb-0 mr-2">Client:<small>(optional)</small></label>
                    <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                        <option></option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label for="project-select" class="font-weight-bold mb-0 mr-2">Project:</label>
                    <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                        <option></option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label for="project-select" class="font-weight-bold mb-0 mr-2">Phase:</label>
                    <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                        <option></option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>                      
            </div>
            <div class="row">
                <div class="col-lg-12 border">
                    <h4>Task summary</h4>
                    <div class="project-info mt-3">
                        <span>📅 Start Date: Jan 15, 2024</span>
                        <span>📅 End Date: Jun 30, 2024</span>
                        <span>👥 Team Size: 8 members</span>
                        <span>🎯 Status: In Progress</span>
                    </div>
                </div>
            </div>     
        </div>    



        


        <div class="ibox-content m-b-sm border-bottom">
            <h2 class="mb-4 font-bold text-muted">Select Task</h2>            
            <div class="row">                   
                <div class="col-lg-6">
                    <label for="project-select" class="font-weight-bold mb-0 mr-2">Parent Task:(ajax)</label>
                    <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                        <option></option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label for="project-select" class="font-weight-bold mb-0 mr-2">Sub Task:(ajax)</label>
                    <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                        <option></option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>                       
            </div>          
        </div>

        
        <div class="row">                   
            <div class="col-lg-6">
                <div class="ibox ">
                    
                    <div class="ibox-title d-flex justify-content-between align-items-center pr-4">
                        <h5 class="m-0">Available Developers</h5>
                        <span class="border rounded-sm _label _label-default px-2 py-1 text-xs bg-gray-200">8 Developers</span>
                    </div>


                    <div class="ibox-content">
                        <p  class="m-b-lg">
                            <strong>Nestable</strong> is an interactive hierarchical list. You can drag and drop to rearrange the order. It works well on touch-screens.
                        </p>


                        <div class="row mb-5">
                            <div class="col-lg-6">
                                <label for="project-select" class="font-weight-bold mb-1 mr-2">Parent Designation:</label>
                                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                                    <option></option>
                                    <option>Alaska</option>
                                    <option>California</option>
                                    <option>Delaware</option>
                                    <option>Tennessee</option>
                                    <option>Texas</option>
                                    <option>Washington</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="project-select" class="font-weight-bold mb-1 mr-2">Sub Designation:</label>
                                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                                    <option></option>
                                    <option>Alaska</option>
                                    <option>California</option>
                                    <option>Delaware</option>
                                    <option>Tennessee</option>
                                    <option>Texas</option>
                                    <option>Washington</option>
                                </select> 
                            </div>
                        </div>

                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle dev-card">
                                        <div class="dev-avatar">JS</div>
                                        <div class="dev-info">
                                            <span class="dev-name mb-1">John Smith <span class="dev-role">(Senior Full Stack Develop)</span></span>
                                            
                                            <div class="dev-stats">
                                                <i class="fa fa-briefcase"></i> 3 Active Projects
                                            </div>
                                        </div>
                                        <a href="#" class="btn-view-projects dd-nodrag" data-toggle="tooltip" title="View Projects"><i class="fa fa-info-circle"></i></a>
                                    </div>
                                </li>
                                <li class="dd-item" data-id="2">
                                    <div class="dd-handle dev-card">
                                        <div class="dev-avatar" style="background: #2b6cb0;">AD</div>
                                        <div class="dev-info">
                                            <span class="dev-name mb-1">Alice Doe <span class="dev-role">(Frontend Specialist)</span></span>
                                            <div class="dev-stats">
                                                <i class="fa fa-briefcase"></i> 1 Active Project
                                            </div>
                                        </div>
                                        <a href="#" class="btn-view-projects dd-nodrag" data-toggle="tooltip" title="View Projects"><i class="fa fa-info-circle"></i></a>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <div class="m-t-md">
                            <h5>Serialised Output</h5>
                        </div>
                        
                        {{-- <textarea id="_nestable-output" class="form-control"></textarea> --}}
                        <pre id="nestable-output" class="text-base"></pre>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title d-flex justify-content-between align-items-center pr-4">
                        <h5 class="m-0">Assigned Developers</h5>
                        <span class="border rounded-sm _label _label-default px-2 py-1 text-xs bg-gray-200">5 Developers</span>
                    </div>
                    <div class="ibox-content">
                        <x-flash-message  
                            class="flash-info"
                            title=""
                            message2="Drag developers from the available list to assign them to this project"  
                            :canClose="false" />

                        <x-flash-message  
                            class="flash-warning"
                            title=""
                            message2=" ⚠️ You have unsaved changes. Click 'Confirm Assignments' to save. "  
                            :canClose="true" />

                        <div class="dd" id="nestable2">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="10">
                                    <div class="dd-handle dev-card">
                                        <div class="dev-avatar" style="background: #38a169;">CW</div>
                                        <div class="dev-info">
                                            <span class="dev-name mb-1">Charlie Wilson <span class="dev-role">(DevOps Engineer)</span></span>
                                            <div class="dev-stats">
                                                <i class="fa fa-calendar-check-o"></i> Assigned: Jan 18, 2024
                                            </div>
                                        </div>
                                        <a href="#" class="btn-view-projects dd-nodrag" data-toggle="tooltip" title="View Projects"><i class="fa fa-info-circle"></i></a>
                                    </div>                                    
                                </li>

                                <li class="dd-item" data-id="50">
                                    <div class="dd-handle dev-card">
                                        <div class="dev-avatar" style="background: #e53e3e;">MB</div>
                                        <div class="dev-info">
                                            <span class="dev-name mb-1">Mark Brown <span class="dev-role">(Backend Developer)</span></span>
                                        </div>
                                        <a href="#" class="btn-view-projects dd-nodrag" data-toggle="tooltip" title="View Projects"><i class="fa fa-info-circle"></i></a>
                                    </div>                                    
                                </li>

                                <li class="dd-item" data-id="60">
                                    <div class="dd-handle dev-card">
                                        <div class="dev-avatar" style="background: #efac11;">FG</div>
                                        <div class="dev-info">
                                            <span class="dev-name mb-1">Frank Gary <span class="dev-role">(Backend Developer)</span></span>
                                            <div class="dev-stats">
                                                <i class="fa fa-briefcase"></i> Assigned: Jan 11, 2025
                                            </div>
                                        </div>
                                        <a href="#" class="btn-view-projects dd-nodrag" data-toggle="tooltip" title="View Projects"><i class="fa fa-info-circle"></i></a>
                                    </div>                                    
                                </li>
                            </ol>
                        </div>

                        <div class="d-flex mt-4">
                            <button type="button" id="" class="update-designation-info btn btn-primary flex-fill mr-2 w-100 shadow-sm font-semibold">
                                <i class="fa fa-check mr-1"></i> Confirm
                            </button>
                            <button type="button" class="reset btn btn-danger flex-fill ml-2 w-100 shadow-sm font-semibold" title="refresh page">
                                <i class="fa fa-times mr-1"></i> Cancel
                            </button>
                        </div>






                        <div class="m-t-md">
                            <h5>Serialised Output</h5>
                        </div>

                        {{-- <textarea id="_nestable2-output" class="form-control"></textarea> --}}
                        <pre id="nestable2-output" class="text-base"></pre>
                    </div>
                </div>
            </div>                

        </div>
    </div>

    <!-- Developer Projects Modal -->
    {{-- 
    <div class="modal fade" id="developerProjectsModal" tabindex="-1" role="dialog" aria-labelledby="developerProjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h4 class="modal-title font-bold text-dark" id="developerProjectsModalLabel">
                        <span id="modal-dev-name"></span>'s Projects
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-3">
                    <hr class="mt-0 mb-4">
                    
                    <div id="project-list-container">
                        <!-- Sample Project 1 -->
                        <div class="project-detail-card">
                            <span class="project-title">iOS App Development</span>
                            <div class="project-info-row">
                                <div class="info-item">
                                    <i class="fa fa-user-circle icon-role"></i> Role: Mobile Developer
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-calendar icon-date"></i> Started: Dec 10, 2023
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-calendar-check-o icon-status" style="color: #4a5568;"></i> Assigned: Dec 12, 2023
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-bullseye icon-status"></i> Status: Active
                                </div>
                            </div>
                        </div>

                        <!-- Sample Project 2 -->
                        <div class="project-detail-card">
                            <span class="project-title">Android App Update</span>
                            <div class="project-info-row">
                                <div class="info-item">
                                    <i class="fa fa-user-circle icon-role"></i> Role: Mobile Developer
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-calendar icon-date"></i> Started: Nov 15, 2023
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-calendar-check-o icon-status" style="color: #4a5568;"></i> Assigned: Nov 20, 2023
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-bullseye icon-status"></i> Status: Active
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    --}}
@stop

@section('bootstrap-modals')
    <!-- Developer Projects Modal -->
    <div class="modal fade" id="developerProjectsModal" tabindex="-1" role="dialog" aria-labelledby="developerProjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h3 class="modal-title font-bold text-dark" id="developerProjectsModalLabel">
                        <span id="modal-dev-name"></span>'s Projects
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-3">
                    <hr class="mt-0 mb-4">
                    
                    <div class="border" id='timeline1-wrapper'>
                        <div id="myTimeline1"></div>
                    </div>


                    <div id="proj-info1" class="proj-info">
                        <div class="proj-info-header">
                            <i class="fa fa-info-circle"></i>
                            <h4>Project Details</h4>
                            <button type="button" class="proj-info-close" title="Close">&times;</button>
                        </div>
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-folder-open"></i> Project : </span>
                            <span class="value-text" id="proj-title"></span>
                        </div>
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-align-left"></i> Content : </span>
                            <span class="value-text" id="proj-content"></span>
                        </div>                 
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-calendar"></i> Time Period : </span>
                            <span class="value-text" id="proj-time-period"></span>
                        </div>                    
                    </div>
                    
                </div>

            </div>
        </div>
    </div>
@stop


@section('script-files')
    <!-- Nestable List -->
    <script src="{{asset('js/plugins/nestable/jquery.nestable.js')}}"></script>   

    <script src="{{asset('plugins/jquery.timeline-2.1.3/dist/jquery.timeline.min.js')}}"></script>
 
@stop


@section('javascript')
<script>
    // Complete eventData array for jQuery.Timeline 2
    const eventDataArr = [
        {
            id: 1,
            start: "2026-03-18 11:09",
            end: "2026-07-28 20:08",
            row: 5,
            bgColor: "#0b128f",
            color: "#FFFFFF",
            label: "Project - Quam egetPellentesque amet egetPellentesque ut ultrices egetPellentesque Sed",
            content: "Ut. ut, sapien, tellus erat erat tellus in, aliquet. mauris. eu. est tellus at et sapien, erat dictum erat mauris. ut, et, et, eu. mauris. tellus et ut, et, erat ut. sapien lacus, at et sapien sapien dictum erat ut,."
        },
        {
            id: 2,
            start: "2026-04-11 10:54",
            end: "2026-04-13 14:17",
            row: 2,
            bgColor: "#d6253c",
            color: "#FFFFFF",
            label: "Mauris ligula faucibus mi porttitor risus Mauris sed",
            content: "Non ullamcorper nunc a, libero ullamcorper consectetur nunc nunc Etiam ullamcorper ex. mattis, felis. pharetra. accumsan volutpat felis. pharetra. volutpat ex. ullamcorper mattis, volutpat pharetra. ex. ullamcorper. accumsan dolor, ex. ullamcorper. tempus Etiam ex. volutpat volutpat felis. tempus felis. tempus."
        },
        {
            id: 3,
            start: "2026-03-05 22:03",
            end: "2026-03-08 21:01",
            row: 1,
            bgColor: "#4539a7",
            color: "#FFFFFF",
            label: "At tempor dictum bibendum mattis lectus mauris lorem",
            content: "Ullamcorper mattis quam. a, nisi hendrerit, amet rhoncus. arcu, amet mattis hendrerit, hendrerit, hendrerit, amet arcu, auctor, quam. amet a, posuere auctor, hendrerit, dictum mattis quam. posuere facilisi. hendrerit, rhoncus. amet auctor, hendrerit, a, dictum placerat rhoncus. auctor, facilisi. quam."
        },
        {
            id: 4,
            start: "2026-03-20 10:58",
            end: "2026-03-22 23:45",
            row: 5,
            bgColor: "#4d1104",
            color: "#FFFFFF",
            label: "Ut sociosqu consectetur elementum conubia arcu variusNullam conubia",
            content: "Commodo Vestibulum sit at elit, posuere imperdiet elit, commodo ullamcorper ipsum sit Class imperdiet massa imperdiet, posuere ipsum massa molestie sit Vestibulum Vestibulum elit, molestie Class egestas massa elit, Phasellus Vestibulum imperdiet elit, sit imperdiet leo molestie commodo ullamcorper posuere."
        },
        {
            id: 5,
            start: "2026-04-02 23:57",
            end: "2026-04-04 12:53",
            row: 3,
            bgColor: "#2faca1",
            color: "#FFFFFF",
            label: "Himenaeos Suspendisse commodo sem himenaeos a Suspendisse nibh",
            content: "Feugiat. eleifend euismod, feugiat feugiat turpis eleifend erat.Aenean feugiat. feugiat. lobortis inceptos erat.Aenean euismod, tellus, vehicula euismod, inceptos bibendum feugiat lobortis erat.Aenean auctor justo. feugiat. vehicula auctor eleifend vehicula feugiat justo. feugiat. bibendum euismod, porta bibendum inceptos feugiat. tellus, eleifend."
        },
        {
            id: 6,
            start: "2026-03-18 21:52",
            end: "2026-03-20 17:58",
            row: 5,
            bgColor: "#c21d2b",
            color: "#FFFFFF",
            label: "In finibus magna ullamcorper facilisis ut finibus est",
            content: "Cras pulvinar risus. porttitor pretium facilisis. mattis litora pulvinar nibh.Nunc nibh.Nunc facilisis. litora pretium mattis sit Lorem risus. Cras pulvinar porttitor pretium Lorem Lorem Cras mattis Lorem mi. pretium magna, risus. porttitor pulvinar Lorem pulvinar mi. porttitor sit nibh.Nunc pretium."
        },
        {
            id: 7,
            start: "2026-04-28 02:29",
            end: "2026-04-30 07:08",
            row: 4,
            bgColor: "#60a1f6",
            color: "#101010",
            label: "Justo arcu quis arcu lectus ex nisl ultrices",
            content: "Ut, lobortis tempus dapibus lobortis ut, lobortis adipiscing non bibendum purus ex. bibendum urna mauris bibendum laoreet purus mauris eu bibendum tempus eu lobortis lobortis dapibus mauris facilisi. bibendum facilisi. dapibus eu ex. urna dapibus mauris ut, adipiscing urna adipiscing."
        },
        {
            id: 8,
            start: "2026-04-12 15:20",
            end: "2026-04-14 12:16",
            row: 3,
            bgColor: "#a62cdb",
            color: "#FFFFFF",
            label: "Cursus elit auctor Vestibulum lorem ornare lorem volutpat",
            content: "Taciti sollicitudin torquent torquent facilisi. torquent dapibus, sollicitudin viverra sollicitudin adipiscing Sed torquent erat imperdiet urna, interdum arcu urna, viverra erat adipiscing arcu taciti taciti torquent adipiscing interdum vulputate dapibus, torquent arcu eu. Sed imperdiet Sed Sed torquent taciti urna,."
        },
        {
            id: 9,
            start: "2026-03-24 21:34",
            end: "2026-03-26 19:29",
            row: 4,
            bgColor: "#fffc6d",
            color: "#101010",
            label: "Class maximus nunc pulvinar nunc maximus sagittis Vestibulum",
            content: "Dictum imperdiet dictum Class lobortis facilisis. Class himenaeos. dictum justo ullamcorper. felis. velit felis. velit lobortis In porttitor himenaeos. lobortis Class porta a porttitor imperdiet In velit a In lobortis sapien, In dictum lobortis ullamcorper. dictum a In In Class."
        },
        {
            id: 10,
            start: "2026-03-02 23:46",
            end: "2026-03-04 23:48",
            row: 4,
            bgColor: "#05257c",
            color: "#FFFFFF",
            label: "Dapibus sagittis volutpat Aliquam tincidunt Maecenas Maecenas Phasellus",
            content: "Magna magna orci Suspendisse justo, tortor porttitor orci Morbi magna tortor ullamcorper. Suspendisse Morbi diam ac, Suspendisse tortor facilisis taciti justo, taciti ac, facilisis diam dapibus, tortor facilisis ullamcorper. nunc Morbi diam magna porttitor nunc dapibus, porttitor ullamcorper. dapibus, justo,."
        },
        {
            id: 11,
            start: "2026-04-15 23:06",
            end: "2026-04-18 10:52",
            row: 2,
            bgColor: "#7925db",
            color: "#FFFFFF",
            label: "In laoreet nibhNunc diam ut hendrerit in ut",
            content: "Tellus est, ullamcorper urna, scelerisque nec dictum lorem. urna, massa congue sed. scelerisque ullamcorper est, tellus posuere, nec et pretium eu. est, ullamcorper massa ullamcorper pretium tellus posuere, pretium massa posuere, pretium dictum eu. est, posuere, lorem. est, nec et."
        },
        {
            id: 12,
            start: "2026-03-27 10:12",
            end: "2026-03-29 07:17",
            row: 1,
            bgColor: "#d7bfd4",
            color: "#101010",
            label: "Est est ad nibhNunc egestas est at vel",
            content: "Diam mauris turpis arcu, cursus. porta facilisis. rutrum arcu, consequat. cursus. turpis Maecenas cursus. fermentum rutrum cursus. tortor rhoncus. rutrum rhoncus. turpis posuere porta mauris rhoncus. posuere metus consequat cursus. mauris rutrum fermentum porta consequat diam rutrum posuere posuere mauris."
        },
        {
            id: 13,
            start: "2026-05-21 02:49",
            end: "2026-05-22 06:02",
            row: 5,
            bgColor: "#4baea4",
            color: "#101010",
            label: "Scelerisque lobortis maximus sit est interdum sit Lorem",
            content: "Mauris, laoreet laoreet varius.Nullam litora In vel facilisis In laoreet Nulla in, mauris, mauris, litora facilisis facilisis tortor Nulla lacinia vel In litora mauris, varius.Nullam Nulla varius.Nullam amet, Nulla Nulla amet, amet, vel Aliquam vel lacinia facilisis varius.Nullam Nulla mauris,."
        },
        {
            id: 14,
            start: "2026-05-20 14:11",
            end: "2026-05-22 15:56",
            row: 4,
            bgColor: "#f72f74",
            color: "#FFFFFF",
            label: "Libero non eros per conubia eu inceptos libero",
            content: "Urna Etiam Sed hendrerit, Sed Vestibulum Vestibulum urna vestibulum Vestibulum eget, Vestibulum Phasellus hendrerit, posuere euismod, Vestibulum vestibulum aptent vitae nunc. sociosqu per tortor euismod, urna eget, nunc. aptent vitae Etiam tortor vitae aptent eget, vestibulum Etiam Sed euismod, Phasellus."
        },
        {
            id: 15,
            start: "2026-03-07 03:34",
            end: "2026-03-10 01:28",
            row: 1,
            bgColor: "#b3da28",
            color: "#101010",
            label: "Ut Ut Proin laoreet Phasellus Ut Phasellus in",
            content: "Eleifend nibh, Etiam tempus nec fermentum nec eleifend Etiam facilisis elit. mauris facilisis facilisis quis, quis, lacus. lacus. Etiam Vivamus lacus. ut, nibh, Vivamus fermentum elit. Vivamus Etiam mauris Etiam fermentum fermentum nisl quis, tincidunt nec nibh, tempus lacus. quis,."
        },
        {
            id: 16,
            start: "2026-03-09 13:00",
            end: "2026-03-10 23:00",
            row: 5,
            bgColor: "#4399f7",
            color: "#FFFFFF",
            label: "NibhNunc ornare pretium consequat condimentum Morbi ornare Suspendisse",
            content: "Eros nibh a cursus dolor nibh ultrices inceptos ultrices nibh nibh nibh eros ultrices est. inceptos Fusce dolor mauris dolor Cras felis mauris inceptos ultrices nibh ultrices Cras inceptos elementum. felis eros a, elementum. Fusce mauris Fusce a, cursus Nam."
        },
        {
            id: 17,
            start: "2026-04-25 19:29",
            end: "2026-04-26 23:42",
            row: 3,
            bgColor: "#adc168",
            color: "#101010",
            label: "Turpis at purus eros eros at purus lectus",
            content: "Leo ex condimentum. hendrerit. feugiat. eget, laoreet mauris, tempor. purus tempor. magna feugiat. tempor. lacinia, laoreet purus mauris, lacinia, magna eget, feugiat. eget, magna lacinia, turpis feugiat. laoreet purus mauris, tempor. hendrerit. velit ex tempor. lacinia, varius.Nullam purus purus magna."
        },
        {
            id: 18,
            start: "2026-03-05 00:33",
            end: "2026-03-07 20:22",
            row: 4,
            bgColor: "#170d9d",
            color: "#FFFFFF",
            label: "Mollis Integer Aliquam mauris leo ex a lorem",
            content: "A, vitae facilisis. Vestibulum lobortis lobortis viverra viverra maximus, posuere. quis viverra a, rutrum. maximus, facilisis. rutrum. ut nunc lobortis a, vitae viverra turpis posuere. posuere. aliquam, viverra maximus, ut viverra rutrum. Vestibulum lacus. quis lobortis viverra maximus, lobortis viverra."
        },
        {
            id: 19,
            start: "2026-05-05 10:10",
            end: "2026-05-07 10:03",
            row: 4,
            bgColor: "#5dbd61",
            color: "#101010",
            label: "Bibendum ut bibendum pharetra mattis pharetra facilisis mattis",
            content: "Diam facilisis. lacus, molestie sed, aliquam pulvinar neque facilisis. magna, molestie lacus, lacus, magna, mauris, magna, imperdiet neque bibendum. facilisis. lacus, pulvinar imperdiet lacus, magna, posuere. diam Cras Cras lacinia, mauris, bibendum. sed, lorem. diam diam posuere. Cras mauris, sed,."
        },
        {
            id: 20,
            start: "2026-04-21 21:22",
            end: "2026-04-24 04:17",
            row: 3,
            bgColor: "#f56d52",
            color: "#101010",
            label: "In Integer mauris mauris Curabitur In lorem mauris",
            content: "Augue taciti mauris nisi. ligula. quis, nisi. bibendum orci ultricies bibendum nisi. orci ultricies mauris eleifend Cras ultricies. Cras bibendum taciti dapibus felis nisi. Cras felis eleifend nisi. orci maximus augue maximus taciti taciti quis, quis, ultricies bibendum augue eleifend."
        },
        {
            id: 21,
            start: "2026-04-08 01:04",
            end: "2026-04-09 01:54",
            row: 5,
            bgColor: "#386bc3",
            color: "#FFFFFF",
            label: "Cursus Suspendisse elit mi ex mi tristique Mauris",
            content: "Porta porta eget.Pellentesque faucibus magna consequat leo velit vel elit, posuere, lectus. lectus. velit faucibus lectus. faucibus consequat varius porta velit libero posuere, leo vel vel elit, faucibus facilisi. consequat vel magna lectus. consequat facilisi. libero elementum. faucibus elit, elementum."
        },
        {
            id: 22,
            start: "2026-04-04 10:32",
            end: "2026-04-06 05:04",
            row: 3,
            bgColor: "#816905",
            color: "#FFFFFF",
            label: "Sed dapibus taciti scelerisque arcu arcu Ut Ut",
            content: "Faucibus porttitor aliquam porttitor non, sociosqu porttitor auctor aliquam faucibus elit. elit. sociosqu aliquam urna. non, Fusce dui aliquam dui tellus aliquam auctor tellus sociosqu elit. elit. justo, Fusce Maecenas Fusce non, sociosqu Fusce porttitor faucibus porttitor sociosqu sociosqu dui."
        },
        {
            id: 23,
            start: "2026-05-22 22:50",
            end: "2026-05-24 22:58",
            row: 5,
            bgColor: "#e8ca00",
            color: "#101010",
            label: "Ipsum In pharetra mauris tellus mattis lacinia elit",
            content: "Et pharetra consequat Maecenas amet, a amet amet et pharetra eleifend consequat amet pharetra hendrerit, lacus, eget, amet amet, hendrerit, Maecenas est lacus, consequat mi, eget, lacus, amet, amet pharetra Maecenas hendrerit, a lacus. sem lacus, eleifend et a et."
        },
        {
            id: 24,
            start: "2026-05-13 20:06",
            end: "2026-05-15 22:18",
            row: 1,
            bgColor: "#bd6042",
            color: "#FFFFFF",
            label: "Risus lacus metus nulla orci nulla mattis mattis",
            content: "Id orci blandit. metus, nulla est id tempor per ullamcorper nulla id odio ullamcorper id urna ad ad ullamcorper odio urna Lorem varius felis. ad Lorem per est nulla id lacinia, ad varius lacinia, varius blandit. Lorem ad lacinia, tempor."
        },
        {
            id: 25,
            start: "2026-05-13 09:50",
            end: "2026-05-16 04:50",
            row: 1,
            bgColor: "#c90bab",
            color: "#FFFFFF",
            label: "Ipsum facilisis metus odio quam sociosqu commodo hendrerit",
            content: "Facilisis dui. auctor varius.Nullam id dui. varius.Nullam facilisis ex, dui. adipiscing adipiscing odio ipsum, Proin Donec adipiscing Donec lacinia, varius.Nullam adipiscing Ut ex, ex, lacinia, facilisis facilisis Donec Donec facilisis id lacinia, Proin auctor aptent Donec adipiscing lacinia, lacinia, id."
        },
        {
            id: 26,
            start: "2026-04-27 22:28",
            end: "2026-04-30 01:58",
            row: 4,
            bgColor: "#fdcc7f",
            color: "#101010",
            label: "Sollicitudin Donec sollicitudin Proin mauris ut urna Donec",
            content: "Sagittis amet, sed sagittis pulvinar. amet, sagittis erat per maximus ornare iaculis suscipit, suscipit, maximus pulvinar. erat quis, pharetra. Nam quis, per amet, tincidunt suscipit, pulvinar. iaculis ornare a, suscipit, per iaculis pulvinar. ornare maximus sagittis iaculis amet, amet, ornare."
        },
        {
            id: 27,
            start: "2026-03-30 01:34",
            end: "2026-04-01 22:51",
            row: 2,
            bgColor: "#4c6a57",
            color: "#FFFFFF",
            label: "Interdum interdum Fusce eget interdum turpis interdum eu",
            content: "In ullamcorper. mi, Cras id Vivamus Duis vitae tellus, vitae id pulvinar. aliquam mi, pulvinar. tellus, Cras Duis mi, pulvinar. Duis Duis pulvinar. arcu, mi, ullamcorper. ullamcorper. id aliquam Vivamus mattis, leo Vivamus Duis mi, in Duis ullamcorper. lacinia vitae."
        },
        {
            id: 28,
            start: "2026-05-23 01:46",
            end: "2026-05-24 19:33",
            row: 2,
            bgColor: "#0336bb",
            color: "#FFFFFF",
            label: "Metus vulputate euismod viverra magna aptent efficitur ornare",
            content: "Varius.Nullam ultricies. mattis, nibh. mattis, hendrerit. facilisis. congue tincidunt congue tincidunt consequat maximus Vivamus imperdiet Vivamus urna tincidunt imperdiet mattis, congue nibh. maximus ultricies. facilisis. imperdiet Vivamus tincidunt imperdiet tincidunt efficitur. hendrerit. maximus consequat imperdiet facilisis. magna varius.Nullam tincidunt facilisis."
        },
        {
            id: 29,
            start: "2026-04-19 15:17",
            end: "2026-04-20 22:11",
            row: 1,
            bgColor: "#a078bf",
            color: "#FFFFFF",
            label: "Tempus lacinia varius est adipiscing massa lectus quam",
            content: "Lacinia, varius.Nullam mattis hendrerit, sapien varius.Nullam volutpat mattis lacinia, lobortis pharetra lobortis lobortis vehicula lobortis varius.Nullam hendrerit, Sed sapien mattis sapien sapien pharetra posuere, mattis sapien faucibus hendrerit, posuere, hendrerit, elementum posuere, faucibus pharetra volutpat mattis varius.Nullam elementum pharetra Sed."
        },
        {
            id: 30,
            start: "2026-05-12 15:48",
            end: "2026-05-13 18:59",
            row: 5,
            bgColor: "#6ff88a",
            color: "#101010",
            label: "Rhoncus iaculis blandit euismod tellus eu eu iaculis",
            content: "Faucibus hendrerit quam. faucibus interdum faucibus Integer elit, tellus, ipsum. Vivamus hendrerit ipsum. tellus, faucibus faucibus Vivamus quam. hendrerit. neque Integer Integer Integer elit, quam. Vivamus neque faucibus quam. justo cursus. cursus. tellus, faucibus justo neque hendrerit tellus, tellus, quam."
        }
    ];

    const randomColor = () => {
        return '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
    };


    let timelineConfig  = {
        //eventData:eventDataArr,
        //"startDatetime": "2025-08-07",
        //"endDatetime": "2026-08-26",
        disableLimitter:true,
        "scale": "day",
        "type": "bar",
        rowHeight:96,
        "rows": "auto",
        rowHeight:40,
        "minGridSize": 100,
        "headline": {
            "display": true,
            //"title": "Project Assignment Timeline",
            "range": true,
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            },
        },
        "footer": {
            "display": true,
            //"content": "© MAGIC METHODS 2026",
            "content": "",
            "range": true,
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            }
        },
        "sidebar": {
            "sticky": true,
            /*
            "list": [
                " Employee 1 Item of 2nd row Item of 2nd row",
                " Item of 2nd row",
                " Item of 3rd row",
                " Item of 4th row",
                " Item of 5th row"
            ]
            */
        },         
        "ruler": {
            "top": {
                "lines": [
                    "year",
                    "month",
                    "day",
                    "weekday"
                ],
                "height": 26,
                "fontSize": 13,
                //"color": "#fff",
                "color": "#777777",
                "background": "#FFFFFF",
                //"background": "#1ab394",
                "locale": "en-US",
                "format": {
                    "timeZone": "Asia/Colombo",
                    "hour12": false,
                    "year": "numeric",
                    "month": "long",
                    "day": "numeric",
                    "weekday": "short"
                }
            },
            "bottom": {
                "lines": [
                    "week",
                            //"year"
                ],
                "color": "#777777",
                "background": "#FFFFFF",
                "locale": "en-US",
                "format": {
                    "timeZone": "Asia/Colombo",
                    "hour12": false,
                    "year": "numeric",
                    "week": "ordinal"
                }
            }
        },
        "rangeAlign": "center",


        "eventMeta": {
            "display": false,
            "scale": "day",
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            },
            "content": ""
        },

        "reloadCacheKeep": false,
        "zoom": false,
        "debug": true,

    };



    $(document).ready(function(){

        var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                output = list.data('output');
                //const prettyJsonString = JSON.stringify(jsonData, null, 2);
                if (window.JSON) {
                    //output.val(window.JSON.stringify(list.nestable('serialize'), null, 2));//, null, 2));
                    output.html(window.JSON.stringify(list.nestable('serialize'), null, 4));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            var updateAllOutputs = function() {
                updateOutput($('#nestable'));
                updateOutput($('#nestable2'));
            };

            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1,
                maxDepth: 1
            }).on('change', updateAllOutputs);

            // activate Nestable for list 2
            $('#nestable2').nestable({
               group: 1,
               maxDepth: 1
            }).on('change', updateAllOutputs);

            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));
            updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Show Developer Projects Modal
            $(document).on('click', '.btn-view-projects', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stops the event from bubbling up to the parent
                
                const devName = $(this).closest('.dev-card').find('.dev-name').contents().filter(function() {
                    return this.nodeType === 3; // Get text node only, skip nested span
                }).text().trim();
                
                $('#modal-dev-name').text(devName);
                $('#developerProjectsModal').modal('show');
            });






            ////////////////////////////////////////////

            let modal_timelineConfig    = timelineConfig;
            let modal_eventDataArr      = eventDataArr;        

            let $modal_timelineContainer  = $("#timeline1-wrapper");
            let modal_timelineId          = "#myTimeline1";

            let modal_increment = 1;
            let modal_lastId    = 19;


            $('#developerProjectsModal').on('shown.bs.modal', function() {
                const shortString = Math.random().toString(36).substring(2, 8); // '2j8hsk'

                modal_timelineConfig.sidebar.list = [
                    " Employee 1 Item" + shortString,
                    " Item of 2nd row" + shortString,
                    " Item of 3rd row" + shortString,
                    " Item of 4th row" + shortString,
                    " Item of 5th row" + shortString
                ];

                modal_timelineConfig.headline.title = 'Project Assignment Timeline' + shortString;

                

                let date = new Date('2025-08-07');

                date.setDate(date.getDate() + (modal_increment*5));
                let startDate = date.toISOString().split('T')[0];

                date.setDate(date.getDate() + (modal_increment*10));
                let endDate = date.toISOString().split('T')[0];

                const newEvent = {
                        id: modal_lastId + modal_increment,
                        start: startDate,
                        end: endDate,
                        row: modal_increment,
                        bgColor: randomColor(),
                        color:randomColor(),
                        label: "project-[" + modal_increment + ']',
                        content: "text-[" + modal_increment + ']'
                    };
                
                modal_eventDataArr.push(newEvent);
                modal_timelineConfig.eventData   = modal_eventDataArr;

                modal_increment++;
                modal_lastId++;


                const timelineWidget  = $modal_timelineContainer.find(modal_timelineId).Timeline(modal_timelineConfig);


                timelineWidget.Timeline('initialized', function(elm,opts,usrdata){
                    //$('.jqtl-headline-wrapper').append('<div><a href="/" class="btn btn-secondary btn-sm">&laquo; Home</a></div>');
                        
                    $('#myTimeline1 .jqtl-side-index').find('.jqtl-side-index-item').each(function(i,el) {
                        console.log($(this).html());
                        txt = $(this).html();
                        $(this).html('');
                        $(this).append('<div class="font-semibold truncate" style="padding: 1px 5px;max-width:200px">' + txt + '</div>');
                    });

                    $('#myTimeline1 .jqtl-side-index-item').css({
                        //'padding'   : '1px 2px',
                        'font-size' :'12px'
                        // Add more properties if needed
                    });
                    

                    $('#myTimeline1 .jqtl-ruler-line-item').css({'font-family': 'inherit !important'});
                    $('#myTimeline1 .jqtl-event-label').css({'font-size' :'12px'});

                    //console.log(elm);
                    //console.log(opts);
                    const $parent = $(elm).parent();
                    

                    // Keep the plugin's horizontal containment
                    // But explicitly control vertical overflow
                    $parent.css({
                        'overflow-x': 'hidden',  // Keep this (plugin's intention)
                        'overflow-y': 'hidden',  // Add this to prevent vertical scroll
                        'max-width': '100vw'     // Keep this
                    });   
                    

                    timelineWidget.Timeline('openEvent', function (event, eventNodes){              
                        console.log(`The event node with eventID: ${event.eventId} was clickes.`);
                        console.log(event);
                        console.log(eventNodes);
                        // show "The event node with eventID: 1 was clickes." in console
                        console.log(event.content);
                        console.log(event.label);
                        
                        $('#proj-info1 #proj-title').html(event.label);
                        $('#proj-info1 #proj-time-period').html(event.start + ' - ' + event.end);
                        $('#proj-info1 #proj-content').html(event.content);
                        $('#proj-info1').fadeIn(); // Show the box with a smooth fade
                    });    


                });
            });

            

            $('#developerProjectsModal').on('hidden.bs.modal', function () {
                //const instance = timelineWidget.data('jq.timeline');
                const instance = $modal_timelineContainer.find(modal_timelineId).data('jq.timeline');
                
                if (instance) {
                    console.log('Destroying timeline instance...');
                    
                    // Destroy the timeline
                    instance.destroy();
                    
                    // When jQuery.Timeline initializes, it binds its internal click handlers
                    // using event delegation on the document level, like this internally:
                    //
                    //   $(document).on('click.jq.timeline', '.jqtl-event-node', function() {
                    //       let instance = $(this).closest(...).data('jq.timeline')
                    //       instance._debug(...)
                    //   })
                    //
                    // The key word is "delegation" — the handler lives on the DOCUMENT,
                    // not on the timeline element itself.
                    //
                    // So when you call instance.destroy() and rebuild the HTML,
                    // you destroyed the timeline ELEMENT and its data,
                    // but that handler on DOCUMENT is still alive and listening.
                    //
                    // Next time someone clicks anywhere matching '.jqtl-event-node',
                    // that old document handler fires, tries to find the instance via .data(),
                    // gets undefined (because you destroyed it), and crashes.
                    //
                    // $(document).off('.jq.timeline') removes ALL handlers
                    // that were namespaced under '.jq.timeline' from the document.
                    //
                    // The namespace suffix is the key — it lets you surgically remove
                    // only the plugin's handlers, without touching any other
                    // click handlers you or other plugins attached to the document.
                    //
                    // Without this line, the ghost handler lives on the document forever,
                    // one destroy/regenerate cycle away from crashing.
                    $(document).off('.jq.timeline');// ← ADD THIS: remove all delegated plugin events from document
                            

                    // Clear the element content
                    $modal_timelineContainer.find(modal_timelineId).empty();
                    
                    
                    
                    console.log('Timeline destroyed successfully');

                    $modal_timelineContainer.removeAttr('style');

                    $modal_timelineContainer.html(`
                        <div id="myTimeline1"></div>
                    `);  
                     $('#proj-info1').hide();   
                }


            });



            // Close button functionality
            $(document).on('click', '#proj-info1 .proj-info-close', function() {
                $('#proj-info1').fadeOut();
            });

            
       });    
</script>
@stop


