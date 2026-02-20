@extends('layouts.master',['title' => 'Edit Tasks'])
@section('title','edit-tasks')



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



    /* 2x2 Info Grid Styling */
    .info-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }
    .info-badge {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 12px 20px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        /* Flex basis for 2x2 grid (minus gap) */
        flex: 1 1 calc(50% - 15px);
        min-width: 250px;
        transition: all 0.2s ease;
    }
    .info-badge:hover {
        background: #ffffff;
        border-color: #cbd5e0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .info-badge i {
        font-size: 26px; /* Bigger Icons */
        margin-right: 18px;
        color: #667eea;
        width: 32px;
        text-align: center;
    }
    .info-badge .info-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #718096;
        display: block;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .info-badge .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #2d3748;
    }
    .text-muted-custom {
        color: #a0aec0;
    }


    /* Form & Section Enhancements */
    .section-header {
        margin-top: 30px;
        margin-bottom: 20px;
    }
    .section-header h5 {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #4a5568 !important;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    .section-header h5 i {
        font-size: 16px;
        margin-right: 10px;
        /*color: #667eea;*/
    }
    .section-header hr {
        margin: 0;
        border-top: 2px solid #edf2f7;
    }

    /* Read-only Display Styling */
    .display-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: flex-start;
    }
    .display-row:last-child {
        border-bottom: none;
    }
    .display-label {
        font-weight: 700;
        color: #64748b;
        font-size: 13px;
        flex: 0 0 25%;
        padding-right: 20px;
    }
    .display-value {
        font-size: 14px;
        color: #1e293b;
        flex: 1;
        font-weight: 500;
    }
    .display-value-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 15px;
        min-height: 40px;
        display: block;
        width: 100%;
    }
    .badge-status {
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }





    /* Horizontal Form Styling */
    .form-horizontal-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: flex-start;
    }
    .form-horizontal-row.no-border {
        border-bottom: none;
    }
    .form-label {
       {{--  font-weight: 700;
        color: #64748b;
        font-size: 13px; --}}
        flex: 0 0 30%;
        padding-right: 20px;
        margin-bottom: 0;
        padding-top: 8px;
    }
    .form-field {
        flex: 1;
    }
    .form-grid-2 {
        {{-- display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 30px; --}}
    }
    .form-control {
        {{-- border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 14px;
        color: #1e293b;
        transition: all 0.2s; --}}
    }
    .form-control:focus {
        {{-- border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none; --}}
    }
    .input-group-text {
       {{--  background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600; --}}
    }








    </style>
    @stop


    @section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        
        <h2 class="m-0 font-bold text-dark">Task Context</h2>
            

        <div class="info-group">
            <!-- Project Detail -->
            <div class="info-badge">
                <i class="fa fa-briefcase"></i>
                <div>
                    <span class="info-label">Project</span>
                    <span class="info-value">FutureSoft ERP Update</span>
                </div>
            </div>

            <!-- Client Detail -->
            <div class="info-badge">
                <i class="fa fa-building-o"></i>
                <div>
                    <span class="info-label">Client</span>
                    <span class="info-value">Global Solutions Inc.</span>
                </div>
            </div>

            <!-- Parent Task Detail -->
            <div class="info-badge">
                <i class="fa fa-level-up"></i>
                <div>
                    <span class="info-label">Parent Task</span>
                    <span class="info-value">Database Migration Module</span>
                </div>
            </div>

            <!-- Project Status -->
            <div class="info-badge">
                <i class="fa fa-bullseye" style="color: #38a169;"></i>
                <div>
                    <span class="info-label">Project Status</span>
                    <span class="info-value">In Progress (65%)</span>
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
                    <h2 class="mb-4 font-bold text-muted">Task Details</h2>    
                            
                                

                                
                        <form id="task-edutupdate-form" action="">
                            <!-- Basic Information -->
                            <div class="section-header mb-3">
                                <h5 class="text-primary font-bold"><i class="fa fa-info-circle mr-1"></i> Basic Information</h5>
                                <hr class="mt-1 mb-3">
                            </div>
                            
                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-name">Task Name <span class="text-danger">*</span></label>
                                <div class="form-field">
                                    <textarea class="form-control task_name w-100" id="task-name" data-source="" placeholder="Enter task name" style="min-height: 45px;"></textarea>
                                    <small class="form-text text-muted">A concise summary of the work (max 150 chars).</small>
                                </div>
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-desc">Description</label>
                                <div class="form-field">
                                    <textarea class="form-control w-100" rows="6" id="task-desc" name="description" placeholder="Provide detailed instructions or context..."></textarea>
                                </div>
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-status">Task Status</label>
                                <div class="form-field">
                                    <select class="form-control" id="task-status" name="status">
                                        <option value="open">Open</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                    <small class="form-text text-muted">Closed tasks become read-only.</small>
                                </div>
                            </div>

                            <!-- Effort & Time Tracking -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="text-primary font-bold"><i class="fa fa-hourglass-o mr-1"></i> Effort & Time Tracking</h5>
                                <hr class="mt-1 mb-3">
                            </div>
                            
                            
                            <div class="form-horizontal-row">
                                <label class="form-label">Estimated Duration</label>
                                <div class="form-field">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="est-hours" name="est_hours" min="0" placeholder="Hrs">
                                        <div class="input-group-append"><span class="input-group-text">:</span></div>
                                        <input type="number" class="form-control" id="est-mins" name="est_mins" min="0" max="59" placeholder="Mins">
                                    </div>
                                </div>
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label">Actual Time Spent</label>
                                <div class="form-field">
                                    <div class="input-group">
                                        <input type="number" class="form-control bg-light" id="actual-hours" name="actual_hours" placeholder="Hrs">
                                        <div class="input-group-append"><span class="input-group-text">:</span></div>
                                        <input type="number" class="form-control bg-light" id="actual-mins" name="actual_mins" placeholder="Mins">
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Schedule & Deadlines -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="text-primary font-bold"><i class="fa fa-calendar-o mr-1"></i> Schedule & Deadlines</h5>
                                <hr class="mt-1 mb-3">
                            </div>

                            
                            <div class="form-horizontal-row">
                                <label class="form-label" for="assigned-at">Assigned Date</label>
                                <div class="form-field">
                                    <input type="datetime-local" class="form-control" id="assigned-at" name="assigned_at" value="{{ date('Y-m-d\TH:i') }}" disabled>
                                </div>
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label" for="deadline">Deadline</label>
                                <div class="form-field">
                                    <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                                </div>
                            </div>

                            <div class="form-horizontal-row no-border">
                                <label class="form-label" for="finished-at">Finished Date</label>
                                <div class="form-field">
                                    <input type="datetime-local" class="form-control" id="finished-at" name="finished_at">
                                </div>
                            </div>
                            

                            <!-- Task Classification -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="text-primary font-bold"><i class="fa fa-tags mr-1"></i> Classification & Status</h5>
                                <hr class="mt-1 mb-3">
                            </div>

                            
                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-priority">Priority</label>
                                <div class="form-field">
                                    <select class="form-control" id="task-priority" name="priority">
                                        <option value="critical">Critical</option>
                                        <option value="high">High</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-progress">Progress</label>
                                <div class="form-field">
                                    <select class="form-control" id="task-progress" name="progress">
                                        <option value="not_started">Not Started</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="blocked">Blocked</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            





                            <!-- Collaboration -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="text-primary font-bold"><i class="fa fa-comments mr-1"></i> Collaboration</h5>
                                <hr class="mt-1 mb-3">
                            </div>

                            <div class="form-horizontal-row">
                                <label class="form-label" for="task-comments">Comments / Notes</label>
                                <div class="form-field">
                                    <textarea class="form-control w-100" rows="5" id="task-comments" name="comments" placeholder="Add initial notes or comments..."></textarea>
                                </div>
                            </div>

                            <div class="form-horizontal-row no-border mt-3">
                                <div class="form-label"></div>
                                <div class="form-field">
                                    <button type="submit" class="btn btn-primary px-4 font-bold shadow-sm" id="btn-save-task">
                                        <i class="fa fa-save mr-2"></i> Save
                                    </button>
                                    <button type="button" class="btn btn-danger ml-2 px-4 shadow-sm border" id="btn-cancel-task">
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <!-- Ownership & Assignment -->
                            <div class="section-header mt-5 mb-4">
                                <h5 class="text-primary font-bold"><i class="fa fa-user-circle mr-1"></i> Ownership & Assignment</h5>
                                <hr class="mt-1 mb-3">
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light border-0 shadow-none mb-3">
                                        <div class="card-body p-3 border-gray-200 border rounded">
                                            <div class="text-xs text-muted mb-1 text-uppercase font-bold">Created By</div>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-primary"><i class="fa fa-user-circle-o fa-2x"></i></div>
                                                <div>
                                                    <div class="font-bold text-sm">Admin User</div>
                                                    <div class="text-xs text-muted">Jan 10, 2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light border-0 shadow-none mb-3">
                                        <div class="card-body p-3 border-gray-200 border rounded">
                                            <div class="text-xs text-muted mb-1 text-uppercase font-bold">Assigned By</div>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-info"><i class="fa fa-user-circle-o fa-2x"></i></div>
                                                <div>
                                                    <div class="font-bold text-sm">Project Manager</div>
                                                    <div class="text-xs text-muted">Jan 12, 2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light border-0 shadow-none mb-3">
                                        <div class="card-body p-3 border-gray-200 border rounded">
                                            <div class="text-xs text-muted mb-1 text-uppercase font-bold">Assigned To</div>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-success"><i class="fa fa-user-circle-o fa-2x"></i></div>
                                                <div>
                                                    <div class="font-bold text-sm">John Doe</div>
                                                    <div class="text-xs text-muted">Developer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                                
                  

                </div>
            </div>

        </div>
    </div>

    
@stop




@section('script-files')
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="{{ asset('plugins/jstree/dist/jstree.js')}}"></script>

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
    const $updateForm = $("#task-edutupdate-form");
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
        const $textareas = $('#task-edit-form').find('textarea');
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

    
});
</script>
@stop