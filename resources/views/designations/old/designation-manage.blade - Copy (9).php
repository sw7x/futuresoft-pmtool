@extends('layouts.master',['title' => 'Empty'])
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
                    
                    <div class="line-through">after delete clear the form</div>
                    <div class="line-through">after reset form load old values</div>
                    <div class="line-through">when create enter name</div>
                    <div class="line-through">descriptin, status also update</div>
                    <div class="line-through">when  create new item then change right side form values to newly item values</div>
                    <div class="line-through">when newly created utem select update right side form</div>

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
                    <button type="button" class="btn btn-warning" onclick="resetModalForm()">
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

    window.onload=function(){
        $(function() {
            
            let data = [{
                "id": "p1",
                "parent": "#",
                "text": "Parent-1",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    "data-description": "This is the first parent designation -p1",
                    "data-enable": true
                }
            }, 
            {
                "id": "p2",
                "parent": "#",
                "text": "Parent-2",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    "data-description": "This is the second parent designation -p2",
                    "data-enable": true
                }
            }, 
            {
                "id": "p2-c1",
                "parent": "p2",
                "text": "child-1",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "First child under Parent-2 -p2-c1",
                    "data-enable": true
                }
            }, 
            {
                "id": "p2-c2",
                "parent": "p2",
                "text": "child-2",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "Second child under Parent-2 -p2-c2",
                    "data-enable": true
                }
            },
            {
                "id": "p3",
                "parent": "#",
                "text": "Parent-3",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    "data-description": "This is the third parent designation -p3",
                    "data-enable": false
                }
            },
            {
                "id": "p4",
                "parent": "#",
                "text": "Parent-4",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    "data-description": "This is the fouth parent designation -p4",
                    "data-enable": true
                }
            },
            {
                "id": "p4-c1",
                "parent": "p4",
                "text": "child-1",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "First child under Parent-4 -p4-c1",
                    "data-enable": false
                }
            }, 
            {
                "id": "p4-c2",
                "parent": "p4",
                "text": "child-2",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "Second child under Parent-4 -p4-c2",
                    "data-enable": true
                }
            },
            {
                "id": "p4-c3",
                "parent": "p4",
                "text": "child-3",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "third child under Parent-4 -p4-c3",
                    "data-enable": false
                }
            }, 
            {
                "id": "p4-c4",
                "parent": "p4",
                "text": "child-4",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "fourth child under Parent-4 -p4-c4",
                    "data-enable": true
                }
            },
            {
                "id": "p5",
                "parent": "#",
                "text": "Parent-5",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    "data-description": "This is the fouth parent designation -p5",
                    "data-enable": false
                }
            },
            {
                "id": "p5-c1",
                "parent": "p5",
                "text": "child-1",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "First child under Parent-5 -p5-c1",
                    "data-enable": true
                }
            }, 
            {
                "id": "p5-c2",
                "parent": "p5",
                "text": "child-2",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "Second child under Parent-5 -p5-c2",
                    "data-enable": false
                }
            },
            {
                "id": "p5-c3",
                "parent": "p5",
                "text": "child-3",
                "type":"child",
                "li_attr" : { 
                    "class" : "child",
                    "data-description": "third child under Parent-5 -p5-c3",
                    "data-enable": true
                }
            }];

            //let data =[];


            $("#designation_jstree").jstree({
                "core": {
                    "check_callback": true,
                    "data": data,
                    "themes" : { "stripes" : true },
                    "force_text": false,
                    "allow_reselect": true
                },
                "types" : {
                    "child" : {
                        "icon" : "fa fa-file-o"
                    },
                    "parent" : {
                        "icon" : "fa fa-folder-o"
                    }
                },
                //"plugins" : [ "unique","wholerow"],
                "plugins" : [ "unique", "types"],
                "unique": {

                    //todo-check
                    "duplicate": function (name, counter) {
                        alert('duplicate node added: ' + name);
                            //return name; // This would just return the duplicate name to use as the node is created
                    }
                },

            }).on('ready.jstree', function() {
                

            }).on('create_node.jstree', function(e, data) {
                console.log('saved');
                alert('saved');

                let level=data.node.parents.length;
                console.log(level);

            }).on('select_node.jstree', function(event, data){
                alert('select');
                console.log(data.selected[0]);
                //console.log(data.selected[0].text());
                console.log( $('#' + data.node.id + ' > .jstree-anchor').attr('aria-level') );


                let level = data.node.parents.length;
                let text  = data.node.text;
                let id    = data.node.id;

                
                let desc        = $('#' + data.node.id).data('description');
                let status      = $('#' + data.node.id).data('enable');            
                //let desc      = data.node.li_attr['data-description'];
                //let status    = data.node.li_attr['data-enable'];
                let statusStr   = status.toString();



                console.log(level,text,id, desc,status);

                //set values inthe form#designation-update-form
                $('#designation-update-form #desig-name').val(text);

                // Note: data() updates jQuery's internal cache but NOT the DOM attribute
                // To update both cache AND DOM,
                $('#designation-update-form #desig-name').attr('data-source', id).data('source', id);//setter



                $('#designation-update-form #desig-desc').val(desc);
                $('#designation-update-form #desig-status').val(statusStr);


                let selectedNode = $("#designation_jstree").jstree("get_selected");
                console.log('selectedNode');
                console.log(selectedNode);
                console.log($('#'+selectedNode[0]).text());


                let node_info = $("#designation_jstree").jstree("get_node",selectedNode[0]);
                console.log(node_info);


                let childrens = node_info.children;
                alert(node_info.children_d.join(','));
                console.log(childrens);

                $("#designation_jstree").jstree("open_node", $('#' + id));
                for(let i=0;i<childrens.length;i++){
                    console.log('~~~' + $('#'+childrens[i]).text());
                }

            });        

        });

    }


    function create_sub(subDesigName, subDesigDesc = '', subDesigStatus = true) {
        if(!subDesigName){
            swal ( "Oops" ,  "Designation(sub) cannot be empty" ,  "error" );
            return false;
        }

        let ref = $('#designation_jstree').jstree(true);
        let parent;
        let sel = ref.get_selected();
        console.log(sel);                
             
        //check parent not select and press sub designation create button
        /*
        if(!sel.length){
            swal ( "Oops" ,  "You didn't select the parent designation" ,  "error" );
            return false;
        }
        */

        //check designation not nested than 2 levels
        /*
        let depth = $('#' + sel + ' > a').attr('aria-level');      
        if(depth >1){
            swal ( "Oops" ,  "must not more than 2 levels" ,  "error" );
            return false;
        }
        */

        sel = sel[0];
        parent = sel;

        //console.log($('#'+parent[0]).text());
        let node_info = $("#designation_jstree").jstree("get_node",parent);
        let childrens = node_info.children;

        // Extract numbers and find the maximum
        //  allChildsOfParent values can be like this p[any number of digits]-c[another any number of digits]
        let numbers = childrens.map(item => item.split('-c')[1]);
        let biggestId   = numbers.length > 0 ? Math.max(...numbers) : 0;
        console.log(biggestId);

        let childrensTxtArr = [];
        childrens.forEach(function(childElemId,index) {
            let childNode = $("#designation_jstree").jstree("get_node",childElemId);                            
            childrensTxtArr.push(childNode.text);
        });
        console.log(childrensTxtArr);

        let isUniqueName = childrensTxtArr.length > 0 ? !childrensTxtArr.includes(subDesigName) : true;
        if(!isUniqueName){
            swal ( "Oops" ,  "Main designation already exists" ,  "error" );
            return false;
        }

        //increment id by 1 for current creating item
        biggestId++;

        sel = ref.create_node(sel, {
            "id"        : parent + "-c" + biggestId,
            //"text"    : "child-"+ k,
            //"text"    : subDesigName + biggestId,
            "text"      : subDesigName,
            "type"      : "child",
            "li_attr" : { 
                "class"             : "child",
                "data-description"  : subDesigDesc,
                "data-enable"       : subDesigStatus
            },            

        }, "last", function() {
            //  alert("Child created");
        });


        if(sel) {
            ref.edit(sel);
        }

        $('#designation_jstree').jstree("deselect_all");

        //$('.jstree').jstree(true).select_node('element id');
        ref.select_node(sel);

        $("#designation_jstree").jstree("open_node", $('#' + sel));

    }


    function create_parent(parentName, parentDesc = '', parentStatus = true) {
        if(!parentName){
            swal ( "Oops" ,  "Designation cannot be empty" ,  "error" );
            return false;
        }

        let ref = $('#designation_jstree').jstree(true);

        //get all parent nodes
        let node_info = $("#designation_jstree").jstree("get_node",'#');
        let parentnodeArrById = node_info.children; //like ["p1", "p12", "p333", "p4", "p52222", "p8333333", "p6"];

        // Extract numbers and find the maximum
        let numbers     = parentnodeArrById.map(item => item.substring(1));
        let biggestId   = numbers.length > 0 ? Math.max(...numbers) : 0;
        console.log(biggestId);
    
        // Get all nodes
        let allNodes = ref.get_json('#', {flat: true});

        // Assuming your array is called something like 'nodes' or 'data'
        let parentObjects = allNodes.filter(obj => obj.type === 'parent');

        let parentTxtArr = parentObjects.map(obj => obj.text);
        let isUniqueName = parentTxtArr.length > 0 ? !parentTxtArr.includes(parentName) : true;

        if(!isUniqueName){
            swal ( "Oops" ,  "Main designation already exists" ,  "error" );
            return false;
        }

        //increment id by 1 for current creating item
        biggestId++;
        
        let sel = ref.create_node('#',  {
            "id"        : "p" + biggestId,
            //"text"    : "Parent-"+ k,
            //"text"    : parentName + "-"+ biggestId,
            "text"      : parentName,
            "type"      : "parent",
            "li_attr" : { 
                "class"             : "parent",
                "data-description"  : parentDesc,
                "data-enable"       : parentStatus
            },
        },"last", function() {
            //alert("Child created");
        });

        if(sel) {
            ref.edit(sel);
        }

        $('#designation_jstree').jstree("deselect_all");

        //$('.jstree').jstree(true).select_node('element id');
        ref.select_node(sel);
    }







    $("#btnDelete").on("click", function() {
        let ref = $('#designation_jstree').jstree(true),
        sel = ref.get_selected();
        if(!sel.length) { return false; }

        let node_info = $("#designation_jstree").jstree("get_node",sel[0]);
        let childrens = node_info.children;
        

        if(childrens.length >0){
            //swal ( "Oops" ,  "Designation has " + childrens.length + 'child designation(s)' ,  "error" );
            swal("Oops" ,  "Designation has " + childrens.length + ' child designation(s)' ,  "error" , {
                buttons: {
                    cancel: "Abort",    
                    confirm: true,
                },
            })
            .then((value) => {
                if(value){
                    swal("delete-confirm");

                    swal({
                        title: "Deleted!",
                        text: "Deleted designation and it's child designations",
                        icon: "success",
                        button: "OK",
                    });

                    ref.delete_node(sel);
                    $('#desig-name').val('');
                    $('#desig-desc').val('');
                    $('#desig-name').attr('data-source', '');
                    $('#desig-status').val('true');

                }  
            });            
        }else{
            ref.delete_node(sel);
            $('#desig-name').val('');
            $('#desig-desc').val('');
            $('#desig-name').attr('data-source', '');
            $('#desig-status').val('true');
        }        

    });





    

    
    //for resetting page right side form
    $("#designation-update-form button.reset").on("click", function() {
        let sourceId = $('#designation-update-form #desig-name').data('source');

        let txt;
        let desc;
        let status;
        
        if(sourceId != ''){
            let  node_info = $("#designation_jstree").jstree("get_node",sourceId);

            txt     = node_info.text;
            desc    = $("#designation_jstree #" + sourceId).data('description');
            status  = $("#designation_jstree #" + sourceId).data('enable');        
        }else{
            txt     = '';
            desc    = '';
            status  = 'true';
        }

        $('#desig-name').val(txt);
        $('#desig-desc').val(desc);
        $('#desig-status').val(status);
    });


    //for resetting modal form
    function resetModalForm() {
        $('#modal-desig-name').val('');
        $('#modal-desig-desc').val('');
        $('#modal-desig-status').val('true');        
    }


    // Open modal button handler
    $(document).on("click", "#btnCreateParent", function() {
        $('#designationDetailsModal #modal-desig-name').removeAttr('data-source').removeData('source');
        
        $('#designationDetailsModal .parent-info-div .parent-desig-name').val('');
        $('#designationDetailsModal .parent-info-div').hide();

        $('#designationDetailsModal').modal('show');
    });

    // Open modal button handler
    $(document).on("click", "#btnCreateSub", function() {               

        let sel = $('#designation_jstree').jstree(true).get_selected();
        
        //check if parent not select click create sub designation button
        if(!sel.length){
            swal ( "Oops" ,  "You didn't select the parent designation" ,  "error" );
            return false;
        }

        //check designation not nested than 2 levels
        let depth = $('#' + sel + ' > a').attr('aria-level');      
        if(depth >1){
            swal ( "Oops" ,  "must not more than 2 levels" ,  "error" );
            return false;
        }


        let selectedNode    = $("#designation_jstree").jstree("get_selected");
        let node_info       = $("#designation_jstree").jstree("get_node",selectedNode[0]); 
        let parentTxt       = node_info.text;
        let parentId        = node_info.id;
        
        $('#designationDetailsModal #modal-desig-name').attr('data-source', parentId).data('source', parentId);

        $('#designationDetailsModal .parent-info-div .parent-desig-name').val(parentTxt);
        $('#designationDetailsModal .parent-info-div').show();

        $('#designationDetailsModal').modal('show');
    });

    
    // Modal update button handler
    $(document).on("click", "#modal-desig-create-btn", function() {
        
        let desigName   = $('#modal-desig-name').val();
        let desigDesc   = $('#modal-desig-desc').val();
        let desigStatus = $('#modal-desig-status').val();
                
        let sourceVal = $('#designationDetailsModal #modal-desig-name').data('source');
        if(typeof sourceVal === 'undefined'){
            create_parent(desigName, desigDesc, desigStatus);
        }else{
            create_sub(desigName, desigDesc, desigStatus);
        }

        resetModalForm();

        // Close the modal
        $('#designationDetailsModal').modal('hide');
    });


    //update designation using form
    $(document).on("click","#desig-update-btn",function() {
        let desigName = $('#desig-name').val();
        let sourceid  = $('#desig-name').attr('data-source'); //setter

        alert(desigName);
        alert(sourceid);

        let desigDesc = $('#desig-desc').val();
        let desigStatus = $('#desig-status').val();



        //$("#designation_jstree").jstree('set_text', '#' + sourceid , desigName );
        $("#designation_jstree").jstree('rename_node', '#' + sourceid , desigName );
        //$("#demo1").jstree('rename_node', node , text );

        let selector = "#designation_jstree #" + sourceid;


        
        // Note: data() updates jQuery's internal cache but NOT the DOM attribute
        // To update both cache AND DOM,           
        $("#designation_jstree #" + sourceid)
            .attr('data-description', desigDesc)
            .data('description', desigDesc);

        // Note: data() updates jQuery's internal cache but NOT the DOM attribute
        // To update both cache AND DOM,
        $("#designation_jstree #" + sourceid)
            .attr('data-enable', desigStatus)
            .data('enable', desigStatus);


        //$("#designation_jstree #" + sourceid).data('description',desigDesc);
        //$("#designation_jstree #" + sourceid).data('enable',desigStatus);


    });


</script>
@stop


