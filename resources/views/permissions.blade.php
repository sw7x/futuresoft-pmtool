@extends('layouts.master',['title' => 'Permissions'])
@section('title','Permissions')




@section('css-files')
    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/jsTree/style.min.css')}}" rel="stylesheet">
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')

    <div class="row" id="_sortable-view">
        <div class="col-lg-12">

            @if(Session::has('message'))
                <x-flash-message  
                    :class="Session::get('cls', 'flash-info')"  
                    :title="Session::get('msgTitle') ?? 'Info!'" 
                    :message="Session::get('message') ?? ''"  
                    :message2="Session::get('message2') ?? ''"  
                    :canClose="true" />
            @endif

                    

            <div class="ibox">
                <div class="ibox-content">                        
                    
                    <div class="form-group row mb-4">
                        <label class="offset-4 col-sm-2 col-form-label font-bold text-lg">Select Role</label>
                        <div class="col-sm-3">
                            <select class="form-control m-b" name="role_id" id="role_select">
                                <option value="">-- Choose Role --</option>
                                <option value="admin">Administrator</option>
                                <option value="project_manager">Project Manager</option>
                                <option value="developer">Developer</option>
                                <option value="manager">Manager</option>
                                <option value="owner">Owner</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-block">Load</button>    
                        </div>
                    </div>
                
                
                    <form method="POST" action="#" class="form-horizontal" id="permissions-form">
                        @csrf
                        
                        <h2 class="mb-4 font-bold text-muted">Permissions Tree</h2>
                        
                        <div id="jstree_permissions" class="m-t-md">
                            <ul>
                                <li data-jstree='{"opened":true}' id="project_manage">Project Management (Master)
                                    <ul>
                                        <li id="project_create">Create Project</li>
                                        <li id="project_view">View Projects</li>
                                        <li id="project_edit">Edit Project</li>
                                        <li id="project_delete">Delete Project</li>
                                        <li id="project_assign_pm">Assign PM</li>
                                        <li id="project_assign_dev">Assign Dev's</li>
                                        <li id="project_view_plan">View Project Plan</li>
                                        <li id="project_view_timeline">View Project Timeline</li>
                                        <li id="project_thread">Project Thread
                                            <ul>
                                                <li id="project_thread_create">Create</li>
                                                <li id="project_thread_post">Post</li>
                                                <li id="project_thread_delete">Delete</li>
                                                <li id="project_thread_view">View</li>
                                                <li id="project_thread_edit">Edit</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li data-jstree='{"opened":false}' id="task_manage">Task Management (Master)
                                    <ul>
                                        <li id="task_create">Create Task</li>
                                        <li id="task_view">View Tasks</li>
                                        <li id="task_edit">Edit Task</li>
                                        <li id="task_delete">Delete Task</li>
                                        <li id="task_assign_dev">Assign Dev's</li>
                                        <li id="task_thread">Task Thread
                                            <ul>
                                                <li id="task_thread_create">Create</li>
                                                <li id="task_thread_post">Post</li>
                                                <li id="task_thread_delete">Delete</li>
                                                <li id="task_thread_view">View</li>
                                                <li id="task_thread_edit">Edit</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                

                                <li data-jstree='{"opened":false}' id="user_manage">User Management (Master)
                                    <ul>
                                        <li id="user_create">Add Users</li>
                                        <li id="user_view">View Users</li>
                                        <li id="user_edit">Edit Users</li>
                                        <li id="user_delete">Delete Users</li>
                                    </ul>
                                </li>
                                <li data-jstree='{"opened":false}' id="system_standalone">System Settings (Standalone)
                                    <ul>
                                        <li id="view_logs">Access System Logs</li>
                                        <li id="manage_backups">Manage Backups</li>
                                        <li id="view_analytics">Access Analytics</li>
                                        <li id="settings_manage">Global Settings</li>
                                    </ul>
                                </li>
                                <li data-jstree='{"opened":false}' id="change_password">Change Password</li>
                                <li data-jstree='{"opened":false}' id="view_permissions">View Permissions</li>
                                <li data-jstree='{"opened":false}' id="edit_profile">Edit Profile</li>
                                <li data-jstree='{"opened":false}' id="view_dashboard">View Dashboard</li>
                            </ul>
                        </div>

                        <!-- Hidden container for selected permissions to be submitted -->
                        <div id="selected_permissions_container"></div>

                        <div class="hr-line-dashed mt-4"></div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" type="submit">Update Permissions</button>
                                <a href="#" class="btn btn-white">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
        </div>
    </div>





@stop




@section('script-files')
    <script src="{{asset('js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('js/plugins/jsTree/jstree.min.js')}}"></script>
@stop


@section('javascript')
<script>
    $(document).ready(function () {
        // Initialize jsTree
        $('#jstree_permissions').jstree({
            'core' : {
                'check_callback' : true
            },
            'plugins' : [ 'checkbox', 'types' ],
            'checkbox': {
                'keep_selected_style': false,
                'three_state': true, // This enables the master/sub hierarchy logic
                'cascade': 'up+down'
            },
            'types' : {
                'default' : {
                    'icon' : 'fa fa-folder text-primary'
                },
                'file' : {
                    'icon' : 'fa fa-file text-success'
                }
            }
        });

        // Form Submission Logic
        $('#permissions-form').submit(function(e) {
            // Get selected IDs from jsTree
            var selectedIds = $('#jstree_permissions').jstree('get_selected');
            
            // Clear previous hidden inputs
            $('#selected_permissions_container').empty();
            
            // Add new hidden inputs for each selected node
            $.each(selectedIds, function(index, id) {
                // Ignore structural nodes that don't represent actual permissions if necessary
                // But usually in Laravel/PHP you want all checked IDs
                $('<input>').attr({
                    type: 'hidden',
                    name: 'permissions[]',
                    value: id
                }).appendTo('#selected_permissions_container');
            });

            // Form will continue to submit with these values
        });
    });</script>
@stop


