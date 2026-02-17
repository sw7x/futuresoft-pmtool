@extends('layouts.master',['title' => 'Task Assign'])
@section('title','Task Assign')


@section('css-files')
@stop

@section('page-css')
    <style>
        .dd-handle.dev-card {
            height: auto;
            padding: 5px 10px;
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
        .info-item .icon-role { color: #4a5568; }
        .info-item .icon-date { color: #e53e3e; }
        .info-item .icon-status { color: #38a169; }
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
            <h2 class="mb-4 font-bold text-muted">Select Task</h2>
            <div class="row">                   

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

                <div class="col-lg-4">
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


            <div class="m-b-md">
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Developer:</label>
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
@stop




@section('script-files')
    <!-- Nestable List -->
    <script src="{{asset('js/plugins/nestable/jquery.nestable.js')}}"></script>    
@stop


@section('javascript')
<script>
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

            
       });    
</script>
@stop


