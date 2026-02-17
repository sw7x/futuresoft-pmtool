@extends('layouts.master',['title' => 'Assign Designations'])
@section('title','assign-designations')




@section('css-files')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="{{ asset('plugins/jstree/dist/themes/default/style.min.css')}}" />    
@stop




@section('page-css')
<style>
    /* Assignment Interface Styling */
    .assignment-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }
    .assignment-header {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f5f9;
    }
    .assignment-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }
    .assignment-body {
        display: flex;
        padding: 15px 15px;
        background: #f8fafc;
        gap: 20px;
    }
    .user-list-box {
        flex: 1;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0px;
        display: flex;
        flex-direction: column;
        min-height: 450px;
    }
    .list-box-header {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .list-box-header h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }
    .count-badge {
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
    }
    .count-badge.assigned {
        background: #ecfdf5;
        color: #10b981;
    }
    .search-container {
        padding: 0 15px 15px 15px;
    }
    .search-input-group {
        position: relative;
    }
    .search-input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    .search-input-group .form-control {
        padding-left: 35px;
        height: 35px;
        font-size: 12px;
        border-color: #e2e8f0;
        border-radius: 0px;
    }
    .selection-bar {
        padding: 10px 15px;
        background: #ed55650d;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 600;
    }
    .selection-bar.assigned {
        background: #1ab3940a;
    }
    .selection-bar .text-green { color: #10b981; }

    .user-items-list {
        flex: 1;
        overflow-y: auto;
        padding: 5px 0;
    }
    .user-item-row {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        transition: background 0.2s;
        cursor: pointer;
    }
    .user-item-row:hover {
        background: #f8fafc;
    }
    .user-checkbox {
        margin-right: 15px;
    }
    .initials-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .user-info-text {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.2;
    }
    .user-desig {
        font-size: 11px;
        color: #94a3b8;
    }

    .middle-actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 15px;
        width: 80px;
    }
    .middle-actions .btn{
        font-weight: bold;
    }
    .action-hint {
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        line-height: 1.4;
    }

    .assignment-footer {
        padding: 15px 15px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
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
                    <div class="row">
                        
                        <div class="col-md-3 px-1">
                            <div class="card mb-3 h-100">

                                <div class="card-header bg-primary text-white _bg-white border-bottom d-flex align-items-center" style="padding: 10px 15px;">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 rounded d-flex align-items-center justify-content-center">
                                            <i class="fa fa-sitemap"></i>
                                        </div>
                                        <div class="lh-1">
                                            <h4 class="m-0 font-bold" style="font-size: 16px;">Designation Tree</h4>
                                        </div>
                                    </div>
                                </div>

                                <div id="" class="card-body box-container border-bottom overflow-hidden px-1 pt-2">
                                    <div id="designation_jstree"></div>
                                </div>                            
                            
                            </div>
                        </div>

                        <div class="col-md-9 pl-2 pr-1 ">
                            
                            <!-- 
                            <div class="row">
                                <div class="col-lg-12">                                    
                                    <div class="designation-summary-card d-flex align-items-center bg-primary" style="min-height: 60px; margin-top: 0;">
                                        <div class="summary-left">
                                            <span class="summary-badge">Design</span>
                                            <div class="summary-content">
                                                <h3 class="summary-title" id="view-desig-title" style="font-size: 16px;">UI Designer</h3>
                                            </div>
                                        </div>
                                        <div class="summary-stats">
                                            <div class="stat-item">
                                                <span class="stat-value" id="view-stat-assigned">3</span>
                                                <span class="stat-label">Assigned</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value" id="view-stat-available">3</span>
                                                <span class="stat-label">Available</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value" id="view-stat-total">6</span>
                                                <span class="stat-label">Total</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            -->

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="assignment-card">
                                        <div class="assignment-header bg-primary">
                                            <h4>Manage Employee Assignments — <span id="header-desig-name">UI Designer</span></h4>
                                        </div>

                                        <div class="assignment-body">
                                            <!-- Available Users -->
                                            <div class="user-list-box">
                                                <div class="list-box-header">
                                                    <h5>Available Users</h5>
                                                    <span class="count-badge">3</span>
                                                </div>
                                                <div class="search-container">
                                                    <div class="search-input-group">
                                                        <i class="fa fa-search"></i>
                                                        <input type="text" class="form-control" placeholder="Search users...">
                                                    </div>
                                                </div>
                                                <div class="selection-bar">
                                                    <div>
                                                        <input type="checkbox" id="select-all-available" class="user-checkbox">
                                                        <label for="select-all-available" class="mb-0">Select all</label>
                                                    </div>
                                                    <span class="text-red">0 selected</span>
                                                </div>
                                                <div class="user-items-list" id="available-users-list">
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #3b82f6;">UA</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">User A UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #8b5cf6;">UB</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">User B UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #d946ef;">UC</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">User C UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Middle Actions -->
                                            <div class="middle-actions">
                                                <button class="btn btn-primary _btn-action btn-add">Add ►</button>
                                                <p class="action-hint">Check users<br>then click</p>
                                                <button class="btn btn-danger _btn-action btn-remove">◄ Remove</button>
                                            </div>

                                            <!-- Assigned Users -->
                                            <div class="user-list-box">
                                                <div class="list-box-header">
                                                    <h5>Assigned Users</h5>
                                                    <span class="count-badge assigned">3</span>
                                                </div>
                                                <div class="search-container">
                                                    <div class="search-input-group">
                                                        <i class="fa fa-search"></i>
                                                        <input type="text" class="form-control" placeholder="Search assigned...">
                                                    </div>
                                                </div>
                                                <div class="selection-bar assigned">
                                                    <div>
                                                        <input type="checkbox" id="select-all-assigned" class="user-checkbox">
                                                        <label for="select-all-assigned" class="mb-0">Select all</label>
                                                    </div>
                                                    <span class="text-green">0 selected</span>
                                                </div>
                                                <div class="user-items-list" id="assigned-users-list">
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #1e3a8a;">SA</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">Staff A UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #7c3aed;">SB</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">Staff B UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                    <div class="user-item-row">
                                                        <input type="checkbox" class="user-checkbox">
                                                        <div class="initials-avatar" style="background: #db2777;">SC</div>
                                                        <div class="user-info-text">
                                                            <span class="user-name">Staff C UI</span>
                                                            <span class="user-desig">UI Designer</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="assignment-footer">
                                            <div class="footer-status">
                                                <strong>3 employees</strong> assigned to <span class="text-primary font-bold">UI Designer</span>
                                            </div>
                                            
                                            <div class="footer-actions">
                                                <div class="form-field">
                                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                                        <i class="fa fa-save mr-2"></i> Save
                                                    </button>
                                                    <button type="button" class="btn btn-danger ml-2 px-4 shadow-sm">
                                                        <i class="fa fa-refresh mr-1"></i> Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
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
    
    // --- Constants & Selectors ---
    const $tree = $("#designation_jstree");
    const $updateForm = $("#designation-update-form");
    const $modal = $("#designationDetailsModal");
    const $modalForm = $("#modal-designation-create");
    
    // --- Initial Data ---
    const data = [
        { "id": "p1", "parent": "#", "text": "Parent-1", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the first parent designation -p1", "data-enable": true } },
        { "id": "p2", "parent": "#", "text": "Parent-2", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the second parent designation -p2", "data-enable": true } },
        { "id": "p2-c1", "parent": "p2", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-2 -p2-c1", "data-enable": true } },
        { "id": "p2-c2", "parent": "p2", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-2 -p2-c2", "data-enable": true } },
        { "id": "p3", "parent": "#", "text": "Parent-3", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the third parent designation -p3", "data-enable": false } },
        { "id": "p4", "parent": "#", "text": "Parent-4", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the fouth parent designation -p4", "data-enable": true } },
        { "id": "p4-c1", "parent": "p4", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-4 -p4-c1", "data-enable": false } },
        { "id": "p4-c2", "parent": "p4", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-4 -p4-c2", "data-enable": true } },
        { "id": "p4-c3", "parent": "p4", "text": "child-3", "type": "child", "li_attr": { "class": "child", "data-description": "third child under Parent-4 -p4-c3", "data-enable": false } },
        { "id": "p4-c4", "parent": "p4", "text": "child-4", "type": "child", "li_attr": { "class": "child", "data-description": "fourth child under Parent-4 -p4-c4", "data-enable": true } },
        { "id": "p5", "parent": "#", "text": "Parent-5", "type": "parent", "li_attr": { "class": "parent", "data-description": "This is the fouth parent designation -p5", "data-enable": false } },
        { "id": "p5-c1", "parent": "p5", "text": "child-1", "type": "child", "li_attr": { "class": "child", "data-description": "First child under Parent-5 -p5-c1", "data-enable": true } },
        { "id": "p5-c2", "parent": "p5", "text": "child-2", "type": "child", "li_attr": { "class": "child", "data-description": "Second child under Parent-5 -p5-c2", "data-enable": false } },
        { "id": "p5-c3", "parent": "p5", "text": "child-3", "type": "child", "li_attr": { "class": "child", "data-description": "third child under Parent-5 -p5-c3", "data-enable": true } }
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
    });

    /**
     * Fills the update form with selected node data
     */
    function populateForm(node) {
        const id = node.id;
        const $li = $("#" + id);
        const desc = $li.data('description') || '';
        const status = $li.data('enable') === false ? 'false' : 'true';

        //$updateForm.find('#desig-name').val(node.text).attr('data-source', id).data('source', id);
        $updateForm.find('#desig-name').val(node.text);
        $updateForm.find('#desig-desc').val(desc);
        $updateForm.find('#desig-status').val(status);

        $tree.jstree("open_node", $li);
    }

});
</script>
@stop