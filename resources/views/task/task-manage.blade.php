@extends('layouts.master',['title' => 'Manage Tasks'])
@section('title','task-manage')




@section('css-files')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="{{ asset('plugins/jstree/dist/themes/default/style.min.css')}}" />    
@stop




@section('page-css')
<style>
    /* Disabled task styling */
    #task_tree_jstree .jstree-node.parent[data-status="closed"] .jstree-anchor {
        opacity: 0.5;
        text-decoration: line-through;
        color: #999 !important;
    }

    #task_tree_jstree .jstree-node.child[data-status="closed"] > .jstree-anchor {
        opacity: 0.5;
        text-decoration: line-through;
        color: #999 !important;
    }

    #task_tree_jstree .jstree-node[data-status="closed"] .jstree-anchor:hover {
        opacity: 0.6;
        background: #ddd;
    }


    /* For all jsTree nodes */
    #task_tree_jstree .jstree-node .jstree-anchor {
        max-width: calc(100% - 20px);
        /* max-width: 450px;  Set your desired max width */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }

    /* To show long text in jsTree with ellipsis (...)*/
    #task_tree_jstree  ul.jstree-children{
        display: block;
    }



    </style>
    @stop


    @section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        <h2 class="mb-4 font-bold text-muted">Select Project</h2>
        <div class="row">                   

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Project:</label>
                    </div>

                    <div class="col-lg-9">
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

        </div>     
    </div>

    
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
                    <br>
                    <div class="row">
                        <div class="col-md-5 pl-1 pr-0">
                            <div class="card mb-3 h-100">

                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-sitemap mr-2"></i> Task Tree View
                                    </h4>
                                </div>

                                <div id="" class="card-body box-container border-bottom overflow-hidden px-2">
                                    <div id="task_tree_jstree"></div>
                                </div> 

                                {{-- 
                                <div class="card-body box-container bg-light">
                                    <div id="jstree" class="bg-white p-3 rounded shadow-sm border h-100"></div>
                                </div>
                                --}}


                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex flex-column p-0">
                                        <button type="button" class="text-left btn btn-primary w-100 mb-2 shadow-sm" id="btnCreateParent">
                                            <i class="fa fa-plus-circle mr-2"></i> Create main
                                        </button>
                                        <button type="button" class="text-left btn btn-info w-100 mb-2 shadow-sm" id="btnCreateSub">
                                            <i class="fa fa-code-fork mr-2"></i> Create sub
                                        </button>
                                        <button type="button" class="text-left btn btn-danger btn-md w-100 shadow-sm" id="btnDelete">
                                            <i class="fa fa-trash mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        

                        <div class="col-md-7 pl-5 pr-1">
                            <div class="card mb-3 h-100">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-address-card-o mr-2"></i> Task Details
                                    </h4>
                                </div>

                                <div id="" class="card-body box-container">
                                    <form id="task-update-form" action="">
                                        <!-- Basic Information -->
                                        <div class="section-header mb-3">
                                            <h5 class="text-primary font-bold"><i class="fa fa-info-circle mr-1"></i> Basic Information</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="task-name">Task Name <span class="text-danger">*</span></label>
                                            
                                            {{-- <input type="text" class="form-control" id="task-name" name="task_name" required maxlength="150" placeholder="Enter task name"> --}}
                                            
                                            <textarea class="form-control task_name" id="task-name" data-source="" placeholder="Enter task name"></textarea>
                                            <small class="form-text text-muted">A concise summary of the work (max 150 chars).</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="task-desc">Description</label>
                                            <textarea class="form-control" rows="6" id="task-desc" name="description" placeholder="Provide detailed instructions or context..."></textarea>
                                        </div>


                                        <div class="form-group">
                                            <label for="task-status">Task Status</label>
                                            <select class="form-control" id="task-status" name="status">
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                            <small class="form-text text-muted">Closed tasks become read-only.</small>
                                        </div>

                                        
                                        <!-- Effort & Time Tracking -->
                                        <div class="section-header mt-5 mb-4">
                                            <h5 class="text-primary font-bold"><i class="fa fa-hourglass-o mr-1"></i> Effort & Time Tracking</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Estimated Duration</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="est-hours" name="est_hours" min="0" placeholder="Hrs">
                                                <div class="input-group-append"><span class="input-group-text">:</span></div>
                                                <input type="number" class="form-control" id="est-mins" name="est_mins" min="0" max="59" placeholder="Mins">
                                            </div>
                                            <small class="form-text text-muted">Predicted time for completion.</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Actual Time Spent</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control bg-light" id="actual-hours" name="actual_hours" placeholder="Hrs">
                                                <div class="input-group-append"><span class="input-group-text">:</span></div>
                                                <input type="number" class="form-control bg-light" id="actual-mins" name="actual_mins" placeholder="Mins">
                                            </div>
                                            {{-- <small class="form-text text-muted">Calculated automatically.</small> --}}
                                        </div>

                                        <!-- Schedule & Deadlines -->
                                        <div class="section-header mt-5 mb-4">
                                            <h5 class="text-primary font-bold"><i class="fa fa-calendar-o mr-1"></i> Schedule & Deadlines</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>




                                        <div class="form-group">
                                            <label for="assigned-at">Assigned Date & Time</label>
                                            <input type="datetime-local" class="form-control" id="assigned-at" name="assigned_at" value="{{ date('Y-m-d\TH:i') }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label for="deadline">Deadline</label>
                                            <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                                            <small class="form-text text-muted">Must be later than assigned date.</small>
                                        </div>

                                        

                                        <div class="form-group">
                                            <label for="finished-at">Finished Date & Time</label>
                                            <input type="datetime-local" class="form-control" id="finished-at" name="finished_at">
                                            <small class="form-text text-muted">Auto-filled on completion.</small>
                                        </div>

                                        <!-- Task Classification -->
                                        <div class="section-header mt-4 mb-3">
                                            <h5 class="text-primary font-bold"><i class="fa fa-tags mr-1"></i> Classification & Status</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>

                                        <div class="form-group">
                                            <label for="task-priority">Priority</label>
                                            <select class="form-control" id="task-priority" name="priority">
                                                <option value="critical">Critical</option>
                                                <option value="high">High</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="low">Low</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="task-progress">Progress</label>
                                            <select class="form-control" id="task-progress" name="progress">
                                                <option value="not_started">Not Started</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                                <option value="blocked">Blocked</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>

                                        {{-- <div class="form-group">
                                            <label for="task-status">Task Status</label>
                                            <select class="form-control" id="task-status" name="status">
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                            <small class="form-text text-muted">Closed tasks become read-only.</small>
                                        </div> --}}

                                        <!-- Collaboration -->
                                        <div class="section-header mt-4 mb-3">
                                            <h5 class="text-primary font-bold"><i class="fa fa-comments mr-1"></i> Collaboration</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>

                                        <div class="form-group">
                                            <label for="task-comments">Comments / Notes</label>
                                            <textarea class="form-control" rows="5" id="task-comments" name="comments" placeholder="Add initial notes or comments..."></textarea>
                                        </div>

                                        <!-- Ownership & Assignment -->
                                        <div class="section-header mt-4 mb-3">
                                            <h5 class="text-primary font-bold"><i class="fa fa-user-circle mr-1"></i> Ownership & Assignment</h5>
                                            <hr class="mt-1 mb-3">
                                        </div>

                                        <div class="form-group">
                                            <label class="mb-1">Created By</label>
                                            <div>
                                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> Admin User</a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="mb-1">Assigned By</label>
                                            <div>
                                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> Project Manager</a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="mb-1">Assignee To</label>
                                            <div>
                                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> John Doe</a>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-top">
                                            <div class="d-flex">
                                                <button type="button" id="task-update-btn" class="btn btn-primary flex-fill mr-2 shadow-sm font-semibold">
                                                    <i class="fa fa-save mr-1"></i> Update Task
                                                </button>
                                                <button type="button" class="reset btn btn-danger flex-fill ml-2 shadow-sm font-semibold">
                                                    <i class="fa fa-refresh mr-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>                           
                        </div>    

                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Task Details Modal -->
    <div class="modal fade" id="taskDetailsModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title" id="taskDetailsModalLabel">
                        <i class="fa fa-plus-circle mr-2"></i> Create Task
                    </h3>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    


                    <form id="modal-task-create" action="">
                        
                        <div class="form-group parent-info-div">
                            <label for="modal-parent-task-name">Parent Task</label>
                            <input type="text" class="form-control parent-task-name" id="modal-parent-task-name" value="" disabled>
                        </div>




                        <div class="form-group">
                            <label for="task-name">Task Name <span class="text-danger">*</span></label>
                            {{-- <input type="text" class="form-control" id="task-name" name="task_name" required maxlength="150" placeholder="Enter task name"> --}}
                            <textarea class="form-control task_name" id="modal-task-name" data-source="" placeholder="Enter task name"></textarea>
                            <small class="form-text text-muted">A concise summary of the work (max 150 chars).</small>

                        </div>

                        <div class="form-group">
                            <label for="task-desc">Description</label>
                            <textarea class="form-control" rows="6" id="modal-task-desc" name="description" placeholder="Provide detailed instructions or context..."></textarea>
                        </div>


                        <div class="form-group">
                            <label for="task-status">Task Status</label>
                            <select class="form-control" id="modal-task-status" name="status">
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                            <small class="form-text text-muted">Closed tasks become read-only.</small>
                        </div>
                        

                        <!-- Effort & Time Tracking -->
                        <div class="section-header mt-5 mb-4">
                            <h5 class="text-primary font-bold"><i class="fa fa-hourglass-o mr-1"></i> Effort & Time Tracking</h5>
                            <hr class="mt-1 mb-3">
                        </div>

                        
                        <div class="form-group">
                            <label>Estimated Duration</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="modal-est-hours" name="est_hours" min="0" placeholder="Hrs">
                                <div class="input-group-append"><span class="input-group-text">:</span></div>
                                <input type="number" class="form-control" id="modal-est-mins" name="est_mins" min="0" max="59" placeholder="Mins">
                            </div>
                            <small class="form-text text-muted">Predicted time for completion.</small>
                        </div>

                        <div class="form-group">
                            <label>Actual Time Spent</label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light" id="modal-actual-hours" name="actual_hours" placeholder="Hrs">
                                <div class="input-group-append"><span class="input-group-text">:</span></div>
                                <input type="number" class="form-control bg-light" id="modal-actual-mins" name="actual_mins" placeholder="Mins">
                            </div>
                            {{-- <small class="form-text text-muted">Calculated automatically.</small> --}}
                        </div>

                        <!-- Schedule & Deadlines -->
                        <div class="section-header mt-5 mb-4">
                            <h5 class="text-primary font-bold"><i class="fa fa-calendar-o mr-1"></i> Schedule & Deadlines</h5>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="form-group">
                            <label for="assigned-at">Assigned Date & Time</label>
                            <input type="datetime-local" class="form-control" id="modal-assigned-at" name="assigned_at" value="{{ date('Y-m-d\TH:i') }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="datetime-local" class="form-control" id="modal-deadline" name="deadline">
                            <small class="form-text text-muted">Must be later than assigned date.</small>
                        </div>

                        

                        <div class="form-group">
                            <label for="finished-at">Finished Date & Time</label>
                            <input type="datetime-local" class="form-control" id="modal-finished-at" name="finished_at">
                            <small class="form-text text-muted">Auto-filled on completion.</small>
                        </div>

                        <!-- Task Classification -->
                        <div class="section-header mt-4 mb-3">
                            <h5 class="text-primary font-bold"><i class="fa fa-tags mr-1"></i> Classification & Status</h5>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="form-group">
                            <label for="task-priority">Priority</label>
                            <select class="form-control" id="modal-task-priority" name="priority">
                                <option value="critical">Critical</option>
                                <option value="high">High</option>
                                <option value="medium" selected>Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="task-progress">Progress</label>
                            <select class="form-control" id="modal-task-progress" name="progress">
                                <option value="not_started">Not Started</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="blocked">Blocked</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        {{-- <div class="form-group">
                            <label for="task-status">Task Status</label>
                            <select class="form-control" id="task-status" name="status">
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                            <small class="form-text text-muted">Closed tasks become read-only.</small>
                        </div> --}}

                        <!-- Collaboration -->
                        <div class="section-header mt-4 mb-3">
                            <h5 class="text-primary font-bold"><i class="fa fa-comments mr-1"></i> Collaboration</h5>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="form-group">
                            <label for="task-comments">Comments / Notes</label>
                            <textarea class="form-control" rows="5" id="modal-task-comments" name="comments" placeholder="Add initial notes or comments..."></textarea>
                        </div>

                        <!-- Ownership & Assignment -->
                        <div class="section-header mt-4 mb-3">
                            <h5 class="text-primary font-bold"><i class="fa fa-user-circle mr-1"></i> Ownership & Assignment</h5>
                            <hr class="mt-1 mb-3">
                        </div>

                        <div class="form-group">
                            <label class="mb-1">Created By</label>
                            <div>
                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> Admin User</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="mb-1">Assigned By</label>
                            <div>
                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> Project Manager</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="mb-1">Assignee To</label>
                            <div>
                                <a href="#" class="text-primary text-sm"><i class="fa fa-user-circle-o mr-1"></i> John Doe</a>
                            </div>
                        </div>





                        

                        
                        
                    </form>


                </div>
                
                <div class="modal-footer">
                    <button type="button" id="modal-task-create-btn" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i> Create
                    </button>
                    <button type="button" class="btn btn-warning" id="btnResetModal">
                        <i class="fa fa-refresh mr-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop




@section('script-files')
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('plugins/jstree/dist/jstree.js')}}"></script>

    <!-- DataTables
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}/"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js')}}/"></script> -->

    <!-- AdminLTE App 
    <script src="{{ asset('js/app.min.js')}}/"></script>-->

    <!-- AdminLTE for demo purposes 
    <script src="{{ asset('js/demo.js')}}/"></script>-->

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@stop




@section('javascript')
<script>
$(function() {
    
    /*
        ==== TODO: fix the process for other values in the task for  ====
        
        Estimated Duration
        Assigned Date & Time
        Deadline
        Actual Time Spent
        Finished Date & Time
        Priority
        Progress
        Comments / Notes
    */








    // --- Constants & Selectors ---
    const $tree = $("#task_tree_jstree");
    const $updateForm = $("#task-update-form");
    const $modal = $("#taskDetailsModal");
    const $modalForm = $("#modal-task-create");
    
    // --- Initial Data ---
    const data = [
        { "id": "p1", "parent": "#", "text": "Parent-1", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the first parent task -p1", "data-status": 'open' } },
        { "id": "p2", "parent": "#", "text": "Parent-2", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the second parent task -p2", "data-status": 'open' } },
        { "id": "p2-c1", "parent": "p2", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-2 -p2-c1", "data-status": 'open' } },
        { "id": "p2-c2", "parent": "p2", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-2 -p2-c2", "data-status": 'open' } },
        { "id": "p3", "parent": "#", "text": "Parent-3", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the third parent task -p3", "data-status": 'closed' } },
        { "id": "p4", "parent": "#", "text": "Parent-4", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the fouth parent task -p4", "data-status": 'open' } },
        { "id": "p4-c1", "parent": "p4", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-4 -p4-c1", "data-status": 'closed' } },
        { "id": "p4-c2", "parent": "p4", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-4 -p4-c2", "data-status": 'open' } },
        { "id": "p4-c3", "parent": "p4", "text": "child-3", "type": "child", "li_attr": { "class": "child", "data-description": "third child under Parent-4 -p4-c3", "data-status": 'closed' } },
        { "id": "p4-c4", "parent": "p4", "text": "child-4", "type": "child", "li_attr": { "class": "child", "data-description": "fourth child under Parent-4 -p4-c4", "data-status": 'open' } },

        {   "id": "p5", 
            "parent": "#", 
            "text": "Since SweetAlert is promise-based, it makes sense to pair it with AJAX functions that are also promise-based. Below is an example of using fetch to search for artists on the iTunes API. Note that we're using content: input in order to both show an input-field and retrieve its value when the user clicks the confirm button: Since SweetAlert is promise-based, it makes sense to pair it with AJAX functions that are also promise-based. Below is an example of using fetch to search for artists on the iTunes API. Note that we're using content: input in order to both show an input-field and retrieve its value when the user clicks the confirm button:", 
            "type": "parent", 
            "li_attr": { 
                "class": "parent", 
                "data-description": "Sometimes, you might run into a scenario where it would be nice to use the out-of-the box functionality that SweetAlert offers, but with some custom UI that goes beyond just styling buttons and text. For that, there's the content option. Sometimes, you might run into a scenario where it would be nice to use the out-of-the box functionality that SweetAlert offers, but with some custom UI that goes beyond just styling buttons and text. For that, there's the content option. Sometimes, you might run into a scenario where it would be nice to use the out-of-the box functionality that SweetAlert offers, but with some custom UI that goes beyond just styling buttons and text. For that, there's the content option. Sometimes, you might run into a scenario where it would be nice to use the out-of-the box functionality that SweetAlert offers, but with some custom UI that goes beyond just styling buttons and text. For that, there's the content option.", 
                "data-status": 'closed' 
            } 
        },
        
        { "id": "p5-c1", "parent": "p5", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-5 -p5-c1", "data-status": 'open' } },
        { "id": "p5-c2", "parent": "p5", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-5 -p5-c2", "data-status": 'closed' } },
        { "id": "p5-c3", "parent": "p5", "text": "child-3", "type": "child", "li_attr": { "class": "child", "data-description": "third child under Parent-5 -p5-c3", "data-status": 'open' } }
    ];
    //const data = [];

    
    // --- Tree Initialization ---
    $tree.jstree({
        "core": {
            "check_callback": true,
            "data": data,
            "themes": { "stripes": true },
            "force_text": false,
            "allow_reselect": true
        },
        "types": {
            "child": { "icon": "fa fa-file-o" },
            "parent": { "icon": "fa fa-folder-o" }
        },
        "plugins": ["unique", "types"],
        "unique": {
            "duplicate": function(name, counter) {
                swal("Oops", 'Duplicate node added: ' + name, "warning");
            }
        }
    }).on('ready.jstree', function() {
        console.log("Tree ready");
    }).on('select_node.jstree', function(event, data) {
        populateForm(data.node);

        //adjust height of the task name, descrption textreas according to content amount
        const $textareas = $('#task-update-form').find('textarea');
        $.each($textareas, function(index, element) {
            $(this).css('height', 'auto');

            // Get scrollHeight
            const scrollHeight = this.scrollHeight; // Direct DOM property
            $(this).css('height', scrollHeight + 'px');
        });

    });

    /**
     * Fills the update form with selected node data
     */
    function populateForm(node) {
        const id     = node.id;
        const $li    = $("#" + id);
        const desc   = $li.data('description') || '';
        const status = $li.data('status');

        $updateForm.find('#task-name').val(node.text).attr('data-source', id).data('source', id);
        //$updateForm.find('#task-name').val(node.text);
        $updateForm.find('#task-desc').val(desc);
        $updateForm.find('#task-status').val(status);

        $tree.jstree("open_node", $li);
    }

    /**
     * Generates a unique ID for new nodes
     */
    function generateNodeId(parentId, type) {
        const ref = $tree.jstree(true);
        const children = parentId === '#' ? ref.get_node('#').children : ref.get_node(parentId).children;
        
        if (type === 'parent') {
            const numbers = children.map(id => parseInt(id.substring(1)) || 0);
            const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
            return "p" + (biggest + 1);
        } else {
            const numbers = children.map(id => (id.includes('-c') ? parseInt(id.split('-c')[1]) : 0));
            const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
            return parentId + "-c" + (biggest + 1);
        }
    }

    /**
     * Checks if a task name is unique within its context
     */
    function isNameUnique(name, parentId) {
        const ref = $tree.jstree(true);
        const siblings = (parentId === '#') 
            ? ref.get_json('#', { flat: true }).filter(n => n.parent === '#' && n.type === 'parent')
            : ref.get_node(parentId).children.map(id => ref.get_node(id));

        const names = siblings.map(node => (node.text || '').toLowerCase());
        return !names.includes(name.toLowerCase());
    }

    window.create_parent = function(name, desc = '', status = true) {
        if (!name) return swal("Oops", "Task cannot be empty", "error");
        if (!isNameUnique(name, '#')) return swal("Oops", "Main task already exists", "error");

        const ref = $tree.jstree(true);
        const newId = generateNodeId('#', 'parent');
        const sel = ref.create_node('#', {
            "id": newId, "text": name, "type": "parent",
            "li_attr": { "class": "parent", "data-description": desc, "data-status": status }            
        }, "last");

        if (sel) {
            ref.deselect_all();
            ref.select_node(sel);
            ref.edit(sel);
        }
    };

    window.create_sub = function(name, desc = '', status = true) {
        if (!name) return swal("Oops", "Task(sub) cannot be empty", "error");

        const ref = $tree.jstree(true);
        const selParent = ref.get_selected();
        if (!selParent.length) return swal("Oops", "Please select a parent Task", "error");

        const parentId = selParent[0];
        if (!isNameUnique(name, parentId)) return swal("Oops", "Task already exists under this parent task", "error");

        const newId = generateNodeId(parentId, 'child');
        const sel = ref.create_node(parentId, {
            "id": newId, "text": name, "type": "child",
            "li_attr": { "class": "child", "data-description": desc, "data-status": status }
        }, "last");

        if (sel) {
            ref.deselect_all();
            ref.select_node(sel);
            ref.open_node(parentId);
            ref.edit(sel);
        }
    };







    // --- Event Listeners ---

    // Delete Node
    $("#btnDelete").on("click", function() {
        const ref = $tree.jstree(true);
        const sel = ref.get_selected();
        if (!sel.length) return;

        const node = ref.get_node(sel[0]);
        const childCount = node.children.length;

        if (childCount > 0) {
            swal({
                title: "Warning",
                text: `Task has ${childCount} child task(s). Delete everything?`,
                icon: "warning",
                buttons: ["Abort", "Delete All"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    ref.delete_node(sel);
                    swal("Deleted!", "Task and its children removed.", "success");
                    $updateForm[0].reset();
                    $updateForm.find('#task-name').removeData('source').removeAttr('data-source');
                }
            });
        } else {
            ref.delete_node(sel);
            $updateForm[0].reset();
            $updateForm.find('#task-name').removeData('source').removeAttr('data-source');
        }
    });

    // Reset Form (Right Side)
    $updateForm.find("button.reset").on("click", function() {
        const ref = $tree.jstree(true);
        const sel = ref.get_selected();
        if (sel) {
            populateForm(ref.get_node(sel));
        } else {
            $updateForm[0].reset();
        }
    });

    // Reset Modal Form Helper
    function resetModalForm() {
        console.log($modalForm);
        $modalForm.find('input[type="text"], textarea, select').not('.parent-task-name').val('');
        $modalForm.find('select#modal-task-status').val('open');     
    }


    // Reset Modal Form Listener
    $(document).on("click", "#btnResetModal", function() {
        resetModalForm();
    });


    // Modal: Open Create Main
    $(document).on("click", "#btnCreateParent", function() {
        resetModalForm();
        $modalForm.find('#modal-task-name').removeData('source').removeAttr('data-source');
        $modalForm.find('#modal-parent-task-name').val('');

        $modal.find('.parent-info-div').hide();
        $modal.modal('show');
    });

    // Modal: Open Create Sub
    $(document).on("click", "#btnCreateSub", function() {
        const ref = $tree.jstree(true);
        const sel = ref.get_selected();

        if (!sel.length) return swal("Oops", "Please select a parent task first", "info");

        const node = ref.get_node(sel[0]);
        const depth = $('#' + node.id + ' > a').attr('aria-level');
        if (depth > 1) return swal("Oops", "Nesting limit is 2 levels", "warning");

        resetModalForm();
        $modal.find('#modal-task-name').attr('data-source', node.id).data('source', node.id);
        $modal.find('.parent-task-name').val(node.text);
        $modal.find('.parent-info-div').show();
        $modal.modal('show');
    });

    // Modal: Confirm Create
    $(document).on("click", "#modal-task-create-btn", function() {
        const name      = $('#modal-task-name').val();
        const desc      = $('#modal-task-desc').val();
        const status    = $('#modal-task-status').val();
        const parentId  = $('#modal-task-name').data('source');

        if (typeof parentId === 'undefined') {
            create_parent(name, desc, status);
        } else {
            create_sub(name, desc, status);
        }

        $modal.modal('hide');
    });

    // Update Node (Right Side)
    $(document).on("click", "#task-update-btn", function() {
        const name      = $('#task-name').val();
        const sourceId  = $('#task-name').data('source');
        const desc      = $('#task-desc').val();
        const status    = $('#task-status').val();

        //if (!sourceId) return swal("Notice", "Select a node to update", "info");

        const ref = $tree.jstree(true);
        ref.rename_node(sourceId, name);

        const $li = $("#" + sourceId);
        $li.attr('data-description', desc).data('description', desc);


        $li.attr('data-status', status).data('status', status);

        swal("Updated", "Task details saved locally", "success");
    });
});
</script>
@stop