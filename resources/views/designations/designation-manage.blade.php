@extends('layouts.master',['title' => 'Manage Designations'])
@section('title','designation-manage')




@section('css-files')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="{{ asset('plugins/jstree/dist/themes/default/style.min.css')}}" />    
@stop




@section('page-css')
<style>
    /* Disabled designation styling */
    #designation_jstree .jstree-node.parent[data-enable="false"] .jstree-anchor {
        opacity: 0.5;
        text-decoration: line-through;
        color: #999 !important;
    }

    #designation_jstree .jstree-node.child[data-enable="false"] > .jstree-anchor {
        opacity: 0.5;
        text-decoration: line-through;
        color: #999 !important;
    }

    #designation_jstree .jstree-node[data-enable="false"] .jstree-anchor:hover {
        opacity: 0.6;
        background: #ddd;
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
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 h-100">

                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-sitemap mr-2"></i> Designation Tree View
                                    </h4>
                                </div>

                                <div id="" class="card-body box-container border-bottom overflow-hidden">
                                    <div id="designation_jstree"></div>
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

                        <div class="col-md-6">
                            <div class="card mb-3 h-100">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-address-card-o mr-2"></i> Designation Details
                                    </h4>
                                </div>

                                <div id="" class="card-body box-container">
                                    <form id="designation-update-form" action="">
                                        <div class="">

                                            <div class="form-group">
                                                <label for="desig-name">Designation Name</label>
                                                <input type="text" class="form-control" id="desig-name" data-source="">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="desig-desc">Description</label>
                                                <textarea class="form-control" rows="5" id="desig-desc"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="desig-status">Status</label>
                                                <select class="form-control" id="desig-status">
                                                    <option value="true">Enable</option>
                                                    <option value="false">Disable</option>
                                                </select>
                                            </div>

                                            <div class="d-flex mt-4">
                                                <button type="button" id="desig-update-btn" class="update-designation-info btn btn-primary flex-fill mr-2 w-100 shadow-sm font-semibold">
                                                    <i class="fa fa-save mr-1"></i> Update
                                                </button>
                                                <button type="button" class="reset btn btn-danger flex-fill ml-2 w-100 shadow-sm font-semibold">
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

    <!-- Designation Details Modal -->
    <div class="modal fade" id="designationDetailsModal" tabindex="-1" role="dialog" aria-labelledby="designationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title" id="designationDetailsModalLabel">
                        <i class="fa fa-plus-circle mr-2"></i> Create Designation
                    </h3>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-designation-create" action="">
                        
                        <div class="form-group parent-info-div">
                            <label for="modal-desig-name">Parent Designation</label>
                            <input type="text" class="form-control parent-desig-name" value="" disabled>
                        </div>

                        <div class="form-group">
                            <label for="modal-desig-name">Designation Name</label>
                            <input type="text" class="form-control" id="modal-desig-name" data-source="">
                        </div>

                        <div class="form-group">
                            <label for="modal-desig-desc">Description</label>
                            <textarea class="form-control" rows="5" id="modal-desig-desc"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="modal-desig-status">Status</label>
                            <select class="form-control" id="modal-desig-status">
                                <option value="true">Enable</option>
                                <option value="false">Disable</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-desig-create-btn" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i> Create
                    </button>
                    <button type="button" id="btnResetModal" class="btn btn-warning">
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

        $updateForm.find('#desig-name').val(node.text).attr('data-source', id).data('source', id);
        $updateForm.find('#desig-name').val(node.text);
        $updateForm.find('#desig-desc').val(desc);
        $updateForm.find('#desig-status').val(status);

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
     * Checks if a designation name is unique within its context
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
        if (!name) return swal("Oops", "Designation cannot be empty", "error");
        if (!isNameUnique(name, '#')) return swal("Oops", "Main designation already exists", "error");

        const ref = $tree.jstree(true);
        const newId = generateNodeId('#', 'parent');
        const sel = ref.create_node('#', {
            "id": newId, "text": name, "type": "parent",
            "li_attr": { "class": "parent", "data-description": desc, "data-enable": status }
        }, "last");

        if (sel) {
            ref.deselect_all();
            ref.select_node(sel);
            ref.edit(sel);
        }
    };

    window.create_sub = function(name, desc = '', status = true) {
        if (!name) return swal("Oops", "Designation(sub) cannot be empty", "error");

        const ref = $tree.jstree(true);
        const selParent = ref.get_selected();
        if (!selParent.length) return swal("Oops", "Please select a parent designation", "error");

        const parentId = selParent[0];
        if (!isNameUnique(name, parentId)) return swal("Oops", "Designation already exists under this parent", "error");

        const newId = generateNodeId(parentId, 'child');
        const sel = ref.create_node(parentId, {
            "id": newId, "text": name, "type": "child",
            "li_attr": { "class": "child", "data-description": desc, "data-enable": status }
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
                text: `Designation has ${childCount} child designation(s). Delete everything?`,
                icon: "warning",
                buttons: ["Abort", "Delete All"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    ref.delete_node(sel);
                    swal("Deleted!", "Designation and its children removed.", "success");
                    $updateForm[0].reset();
                    $updateForm.find('#desig-name').removeData('source').removeAttr('data-source');
                }
            });
        } else {
            ref.delete_node(sel);
            $updateForm[0].reset();
            $updateForm.find('#desig-name').removeData('source').removeAttr('data-source');
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
        $modalForm.find('input[type="text"], textarea, select').not('.parent-desig-name').val('');
        $modalForm.find('select').val('true');        
    }

    // Reset Modal Form Listener
    $(document).on("click", "#btnResetModal", function() {
        //$modalForm[0].reset();
        resetModalForm();
    });



    // Modal: Open Create Main
    $(document).on("click", "#btnCreateParent", function() {
        resetModalForm();
        $modalForm.find('#modal-desig-name').removeData('source').removeAttr('data-source');
        $modal.find('.parent-info-div').hide();
        $modal.modal('show');
    });

    // Modal: Open Create Sub
    $(document).on("click", "#btnCreateSub", function() {
        const ref = $tree.jstree(true);
        const sel = ref.get_selected();

        if (!sel.length) return swal("Oops", "Please select a parent designation first", "info");

        const node = ref.get_node(sel[0]);
        const depth = $('#' + node.id + ' > a').attr('aria-level');
        if (depth > 1) return swal("Oops", "Nesting limit is 2 levels", "warning");

        resetModalForm();
        $modal.find('#modal-desig-name').attr('data-source', node.id).data('source', node.id);
        $modal.find('.parent-desig-name').val(node.text);
        $modal.find('.parent-info-div').show();
        $modal.modal('show');
    });

    // Modal: Confirm Create
    $(document).on("click", "#modal-desig-create-btn", function() {
        const name = $('#modal-desig-name').val();
        const desc = $('#modal-desig-desc').val();
        const status = $('#modal-desig-status').val() === 'true';
        const parentId = $('#modal-desig-name').data('source');

        if (typeof parentId === 'undefined') {
            create_parent(name, desc, status);
        } else {
            create_sub(name, desc, status);
        }

        $modal.modal('hide');
    });

    // Update Node (Right Side)
    $(document).on("click", "#desig-update-btn", function() {
        const name = $('#desig-name').val();
        const sourceId = $('#desig-name').data('source');
        const desc = $('#desig-desc').val();
        const status = $('#desig-status').val();

        //if (!sourceId) return swal("Notice", "Select a node to update", "info");

        const ref = $tree.jstree(true);
        ref.rename_node(sourceId, name);

        const $li = $("#" + sourceId);
        $li.attr('data-description', desc).data('description', desc);
        $li.attr('data-enable', status === 'true').data('enable', status === 'true');

        swal("Updated", "Designation details saved locally", "success");
    });
});
</script>
@stop