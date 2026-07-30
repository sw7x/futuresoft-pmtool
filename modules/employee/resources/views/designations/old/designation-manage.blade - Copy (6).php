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
                    <h1>designation-manage.blade</h1>

                    <div class="line-through">after delete clear the form</div>
                    <div class="line-through">after reset form load old values</div>
                    <div>when create enter name</div>
                    <div class="line-through">descriptin, status also update</div>
                    <div>when  create new item then change right side form values to newly item values</div>
                    <div>when newly created utem select update right side form</div>

                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 h-100">

                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0 font-weight-bold">
                                        <i class="fa fa-sitemap mr-2"></i> Designation Tree View
                                    </h3>
                                </div>

                                <div id="" class="card-body box-container border-bottom">
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
                                        <button type="button" class="text-left btn btn-danger btn-md w-100 shadow-sm" id="btnCreateChild" onclick="demo_delete();">
                                            <i class="fa fa-trash mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3 h-100">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0 font-weight-bold">
                                        <i class="fa fa-sitemap mr-2"></i> Designation Details
                                    </h3>
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
                                                <button type="button" id="desig-update-btn" class="update-designation-info btn btn-primary flex-fill mr-2 w-100 shadow-sm font-weight-bold">
                                                    <i class="fa fa-save mr-1"></i> Update
                                                </button>
                                                <button type="button" class="reset btn btn-warning flex-fill ml-2 w-100 shadow-sm font-weight-bold">
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

        <div class="col-lg-12 mt-3 mb-4">
            <div class="text-center">
                <button type="button" class="btn btn-lg btn-success shadow-sm" id="btnOpenDetailsModal">
                    <i class="fa fa-edit mr-2"></i> modal-create-main
                </button>
            </div>
        </div>

        <div class="col-lg-12 mt-3 mb-4">
            <div class="text-center">
                <button type="button" class="btn btn-lg btn-danger shadow-sm" id="btnOpenDetailsModal">
                    <i class="fa fa-edit mr-2"></i> modal-create-sub
                </button>
            </div>
        </div>





    </div>

    <!-- Designation Details Modal -->
    <div class="modal fade" id="designationDetailsModal" tabindex="-1" role="dialog" aria-labelledby="designationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title" id="designationDetailsModalLabel">
                        <i class="fa fa-sitemap mr-2"></i> Designation Details
                    </h3>                   
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-designation-create" action="">
                        <div class="form-group">
                            <label for="modal-desig-name">Designation Name1</label>
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
                    <button type="button" id="modal-desig-update-btn" class="btn btn-primary">
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
                // Apply disabled styling to nodes with status: false
                /*
                var tree = $('#designation_jstree').jstree(true);
                var allNodes = tree.get_json('#', {flat: true});
                
                allNodes.forEach(function(node) {
                    // Check if node itself has status: false
                    if (node.data && node.data.status === false) {
                        $('#' + node.id).addClass('jstree-node-disabled');
                        
                        // If it's a parent node, also disable all its children
                        var nodeObj = tree.get_node(node.id);
                        if (nodeObj && nodeObj.children_d && nodeObj.children_d.length > 0) {
                            nodeObj.children_d.forEach(function(childId) {
                                $('#' + childId).addClass('jstree-sub-node-disabled');
                            });
                        }
                    }
                });
                */

            }).on('create_node.jstree', function(e, data) {
                console.log('saved');
                alert('saved');

                var level=data.node.parents.length;
                console.log(level);

            }).on('select_node.jstree', function(event, data){
                alert('select');
                console.log(data.selected[0]);
                //console.log(data.selected[0].text());
                console.log( $('#' + data.node.id + ' > .jstree-anchor').attr('aria-level') );


                var level = data.node.parents.length;
                var text  = data.node.text;
                var id    = data.node.id;

                
                var desc    = $('#' + data.node.id).data('description');
                var status    = $('#' + data.node.id).data('enable');            
                //var desc    = data.node.li_attr['data-description'];
                //var status    = data.node.li_attr['data-enable'];
                var statusStr    = status.toString();



                console.log(level,text,id, desc,status);

                //set values inthe form#designation-update-form
                $('#designation-update-form #desig-name').val(text);

                // Note: data() updates jQuery's internal cache but NOT the DOM attribute
                // To update both cache AND DOM,
                $('#designation-update-form #desig-name').attr('data-source', id).data('source', id);//setter



                $('#designation-update-form #desig-desc').val(desc);
                $('#designation-update-form #desig-status').val(statusStr);


                var selectedNode = $("#designation_jstree").jstree("get_selected");
                console.log('selectedNode');
                console.log(selectedNode);
                console.log($('#'+selectedNode[0]).text());


                var node_info = $("#designation_jstree").jstree("get_node",selectedNode[0]);
                console.log(node_info);


                var childrens = node_info.children;
                alert(node_info.children_d.join(','));
                console.log(childrens);

                $("#designation_jstree").jstree("open_node", $('#' + id));
                for(var i=0;i<childrens.length;i++){
                    console.log('~~~' + $('#'+childrens[i]).text());
                }

            });


            //update designation using form
            $(document).on("click","#desig-update-btn",function() {
                var desigName = $('#desig-name').val();
                var sourceid  = $('#desig-name').attr('data-source'); //setter

                alert(desigName);
                alert(sourceid);

                var desigDesc = $('#desig-desc').val();
                var desigStatus = $('#desig-status').val();





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




            $('#btnCreateSub').click(function(){

                var ref = $('#designation_jstree').jstree(true);
                var parent;
                var sel = ref.get_selected();
                console.log(sel);                
                console.log('=============sel');





                var depth = $('#' + sel + ' > a').attr('aria-level');
                alert('depth' + depth);


                if(depth >1){
                        //alert('must not more than 3 levels');
                    swal ( "Oops" ,  "must not more than 2 levels" ,  "error" );
                    return false;
                }

                if(!sel.length) { return false; }

                sel = sel[0];
                parent = sel;




                console.log($('#'+parent[0]).text());

                var node_info = $("#designation_jstree").jstree("get_node",parent);

                console.log(node_info);

                var childrens = node_info.children;

                console.log(node_info.children_d.join(','));
                console.log(childrens);

                var k=1;



                var slectedParent     = $("#designation_jstree").jstree("get_node",$('#' + parent));
                var allChildsOfParent = node_info.children;


                function  checkUniqueId(id){

                    var tempId = parent + "-c" + id;
                    for (var i = 0; i < allChildsOfParent.length; i++)
                    {
                        var loopitemId = allChildsOfParent[i];
                        if (tempId === loopitemId)
                        {
                            return false;
                        }
                    }
                    return id;
                }

                function  checkUniqueName(id){

                    var tempNodeText = "child-"+ id;
                    for (var i = 0; i < allChildsOfParent.length; i++)
                    {
                        var loopitemText = $('#'+allChildsOfParent[i]).text();
                        if (tempNodeText === loopitemText)
                        {
                            return false;
                        }
                    }
                    return tempNodeText;
                }


                var k = 0;
                var isUniqueId   = false;
                var isUniqueName = false;

                while( isUniqueId ===false || isUniqueName ===false ){
                    k++;
                    isUniqueId   = checkUniqueId(k);
                    isUniqueName = checkUniqueName(k);

                }


                sel = ref.create_node(sel, {
                    "id": parent + "-c" + k,
                    "text": "child-"+ k,
                    "type" : "child",
                    "li_attr" : { "class" : "child" },



                }, "last", function() {
                    //  alert("Child created");
                });


                console.log(sel);
                if(sel) {
                    ref.edit(sel);
                }

                $('#designation_jstree').jstree("deselect_all");

                    //$('.jstree').jstree(true).select_node('element id');
                ref.select_node(sel);


                $("#designation_jstree").jstree("open_node", $('#' + sel));


                j++;
            });



            $('#btnCreateParent').click(function() {
                create_parent('Parent');            
                //$('#ddd').modal('show');

                //todo -  if empty  - alert and close
            });













            // not using todo
            {{-- 
            $('#btnCreateChild').click(function() {
                var parentid = $("#designation_jstree").jstree().get_selected()[0]
                console.log(parentid);

                if($('#' + parentid).hasClass('parent')){
                    $('#designation_jstree').jstree().create_node(parentid, {
                        "id": parentid + "c3",
                        "text": "Child 3",
                        "li_attr" : { "class" : "child" },
                    }, "last", function() {
                        alert("Child created");
                    });
                }else{
                    alert("error");
                }
            }); 
            --}}




        });

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


        function  checkUniqueId(_id){

            let tempId = "p" + _id;
            for (let i = 0; i < parentnodeArrById.length; i++)
            {
                let loopitemId = parentnodeArrById[i];
                if (tempId === loopitemId)
                {
                    return false;
                }
            }
            return _id;
        }

        function  checkUniqueName(_parentName){

            //var tempNodeText = "Parent-"+ id;
            //let tempNodeText = parentName + "-"+ id;
            //let tempNodeText = _parentName;
            //let exists = arr.includes(gg);

            //$('#designation_jstree ')
            let tree = $('#designation_jstree').jstree(true);

            // Get all nodes
            let allNodes = tree.get_json('#', {flat: true});


            // Assuming your array is called something like 'nodes' or 'data'
            let parentObjects = allNodes.filter(obj => obj.type === 'parent');

            let parentTxtArr = parentObjects.map(obj => obj.text);
            let isUnique = parentTxtArr.length > 0 ? !parentTxtArr.includes(_parentName) : true;



            // Filter and map to get texts
            //let allTexts = allNodes.map(node => node.text);
            console.log();

            /*

            for (let i = 0; i < parentnodeArrById.length; i++)
            {
                let loopitemText = $('#'+parentnodeArrById[i]).text();
                if (tempNodeText === loopitemText)
                {
                    return false;
                }
            }
            */


            return isUnique;
        }


        //var k=0;

        /*
        //let isUniqueId   = false;
        let isUniqueName = false;

        //while( isUniqueId ===false || isUniqueName ===false ){
        while( isUniqueName === false ){
            biggestId++;
            //isUniqueId   = checkUniqueId(biggestId);
            isUniqueName = checkUniqueName(biggestId);
            //todo - if not unique - alert and close


        }
        */


        
        let isUniqueName = checkUniqueName(parentName);
        if(!isUniqueName){
            swal ( "Oops" ,  "Main designation already exists" ,  "error" );
            return false;
        }



        alert(biggestId);
        
        //increment id by 1 for current creating item
        biggestId++;
        
        let sel = ref.create_node('#',  {
            "id": "p" + biggestId,
                                            //"text": "Parent-"+ k,
            //"text": parentName + "-"+ biggestId,
            "text": parentName,

            "type" : "parent",
            "li_attr" : { 
                "class" : "parent",
                "data-description": parentDesc,
                "data-enable": parentStatus
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

















    function demo_rename() {
        var ref = $('#designation_jstree').jstree(true),
        sel = ref.get_selected();
        if(!sel.length) { return false; }
        sel = sel[0];
        ref.edit(sel);
    }


    function demo_delete() {
        var ref = $('#designation_jstree').jstree(true),
        sel = ref.get_selected();
        if(!sel.length) { return false; }
        ref.delete_node(sel);
        

        $('#desig-name').val('');
        $('#desig-desc').val('');
        $('#desig-name').attr('data-source', '');
        $('#desig-status').val('true');
    }

    
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
        $('#modal-desig-name').attr('data-source', ''); //todo
    }


    // Open modal button handler
    $(document).on("click", "#btnOpenDetailsModal", function() {
        // Open the modal
        $('#designationDetailsModal').modal('show');
    });

    

    //TODO
    // Modal update button handler
    $(document).on("click", "#modal-desig-update-btn", function() {
        

        var desigName = $('#modal-desig-name').val();
        var desigDesc = $('#modal-desig-desc').val();
        var desigStatus = $('#modal-desig-status').val();
        var sourceid = $('#modal-desig-name').attr('data-source');

        {{-- 
        if(!sourceid) {
            alert('Please select a designation from the tree first');
            return false;
        }
        --}}


        create_parent(desigName, desigDesc, desigStatus);

        //alert('Updating: ' + desigName);

        // Update the tree node
        //$("#designation_jstree").jstree('rename_node', '#' + sourceid, desigName);

        // Also update the main form
        //$('#desig-name').val(desigName);
        //$('#desig-desc').val(desigDesc);
       // $('#desig-status').val(desigStatus);


        resetModalForm()
        // Close the modal
        $('#designationDetailsModal').modal('hide');
    });


</script>
@stop


