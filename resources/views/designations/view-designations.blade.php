@extends('layouts.master',['title' => 'View Designations'])
@section('title','view-designations')




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

                                <div id="" class="card-body box-container border-bottom">
                                    <div id="designation_jstree"></div>
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
                                                <input type="text" class="form-control" id="desig-name" data-source="" disabled>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="desig-desc">Description</label>
                                                <textarea class="form-control" rows="5" id="desig-desc" disabled></textarea>
                                            </div>



                                            <div class="form-group">
                                                <label for="desig-status">Status</label>
                                                <select class="form-control" id="desig-status" disabled>
                                                    <option value="true">Enable</option>
                                                    <option value="false">Disable</option>
                                                </select>
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

    

    







    // --- Event Listeners ---

    
});
</script>
@stop