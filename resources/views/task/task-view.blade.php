@extends('layouts.master',['title' => 'View Tasks'])
@section('title','view-tasks')



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

                                        

                                    </form>
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

    
});
</script>
@stop