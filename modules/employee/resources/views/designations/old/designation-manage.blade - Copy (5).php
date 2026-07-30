@extends('layouts.master',['title' => 'Empty'])
@section('title','designation-manage')




@section('css-files')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="{{ asset('plugins/jstree/dist/themes/default/style.min.css')}}" />    
@stop




@section('page-css')
<style>
    /*.list-group.panel > .list-group-item {*/
        /*border-bottom-right-radius: 4px;*/
        /*border-bottom-left-radius: 4px*/
        /*}*/
        /*.list-group-submenu {*/
            /*margin-left:20px;*/
            /*}*/
            /*.list-group-item{*/
                /*width:96%;*/
                /*border:0px;*/
                /*display: block;*/
                /*float: left;*/
                /*}*/
                /*.acc-item-wrapper{*/
                    /*border:1px solid #000;*/


                    /*}*/


        {{-- 

        .nopadding {
            padding:0px;
        }
        .categorylink {
            display: block;
            padding: 12px 10px;
            text-decoration: none;
            float: left;
            width: 94%;

        }
        .categoryupdate {
            display: block;
            padding: 10px 4px;
            text-decoration: none;
            float: right;
            width: 3%;
            border-radius: 0px;

        }
        .deletelink {
            display: block;
            padding: 10px 4px;
            text-decoration: none;
            float: right;
            width: 3%;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 0px;

        }

        .panel-title{
            /*height: 20px;*/
            display:block;

            font-size: 14px;
        }

        .panel-default > .panel-heading {
            color: #333;
            background-color: #f5f5f5;
            border-color: #ddd;
        }
        .docmgCategoryBlock{
            padding: 10px 10px;
        }

        .btn{
            //display: block;
        }


        .box-container {
        //height: 200px;
        }

        .box-item {
            width: 100%;
            z-index: 1000
        }

        .box-container{
            min-height: 500px;
        }
        .assign-emp{
            bottom: 35px;
            right: 30px;
            position : absolute;
        }
        --}}   

    /* Disabled designation styling */
    #designation_jstree .jstree-node-disabled > .jstree-anchor {
        opacity: 0.5;
        text-decoration: line-through;
        color: #999 !important;
    }

    #designation_jstree .jstree-node-disabled > .jstree-anchor:hover {
        opacity: 0.6;
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

                    <div>after delete clear the form</div>
                    <div>after reset form load old values</div>
                    <div>when create enter name</div>
                    <div>descriptin, status also update</div>


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

                                    {{-- <div class="card-body box-container bg-light">
                                        <div id="jstree" class="bg-white p-3 rounded shadow-sm border h-100"></div>
                                    </div>--}}


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
                                        <form id="task-info" action="">
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
                                                        <option value="1">Enable</option>
                                                        <option value="0">Disable</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex mt-4">
                                                    <button type="button" id="desig-update-btn" class="update-task-info btn btn-primary flex-fill mr-2 w-100 shadow-sm font-weight-bold">
                                                        <i class="fa fa-save mr-1"></i> Update
                                                    </button>
                                                    <button type="button" class="btn btn-warning flex-fill ml-2 w-100 shadow-sm font-weight-bold" onclick="resetForm()">
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
                        <i class="fa fa-edit mr-2"></i> Open Designation Details
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
                        <form id="modal-task-info" action="">
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
                                    <option value="1">Enable</option>
                                    <option value="0">Disable</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modal-desig-update-btn" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i> Update
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
            
            var i=3;
            var j=1;
            var data = [{
                "id": "p1",
                "parent": "#",
                "text": "Parent-1",
                "type":"parent",
                "li_attr" : { 
                    "class" : "parent",
                    
                },
                "data": { 
                    "description": "This is the first parent designation",
                    "status" :true
                }
            }, 
            {
                "id": "p2",
                "parent": "#",
                "text": "Parent-2",
                "type":"parent",
                "li_attr" : { "class" : "parent" },
                "data": { 
                    "description": "This is the second parent designation",
                    "status" :true 
                }
            }, 
            {
                "id": "p2-c1",
                "parent": "p2",
                "text": "child-1",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "First child under Parent-2",
                    "status" :true 
                }
            }, 
            {
                "id": "p2-c2",
                "parent": "p2",
                "text": "child-2",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "Second child under Parent-2",
                    "status" :true
                }
            },
            {
                "id": "p3",
                "parent": "#",
                "text": "Parent-3",
                "type":"parent",
                "li_attr" : { "class" : "parent" },
                "data": { 
                    "description": "This is the third parent designation",
                    "status" :false 
                }
            },
            {
                "id": "p4",
                "parent": "#",
                "text": "Parent-4",
                "type":"parent",
                "li_attr" : { "class" : "parent" },
                "data": { 
                    "description": "This is the fouth parent designation",
                    "status" :true 
                }
            },
            {
                "id": "p4-c1",
                "parent": "p4",
                "text": "child-1",
                "type":"child",
                "li_attr" : { 
                    "class" : "child", 
                    'data-enable' : false , 
                    'data-description': "First child under Parent-4 First child under Parent-4"
                },
                "data": { 
                    "description": "First child under Parent-4",
                    "status" : false
                }
            }, 
            {
                "id": "p4-c2",
                "parent": "p4",
                "text": "child-2",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "Second child under Parent-4",
                    "status" :true
                }
            },
            {
                "id": "p4-c3",
                "parent": "p4",
                "text": "child-3",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "third child under Parent-4",
                    "status" :false 
                }
            }, 
            {
                "id": "p4-c4",
                "parent": "p4",
                "text": "child-4",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "fourth child under Parent-4",
                    "status" :true
                }
            },
            {
                "id": "p5",
                "parent": "#",
                "text": "Parent-5",
                "type":"parent",
                "li_attr" : { "class" : "parent" },
                "data": { 
                    "description": "This is the fouth parent designation",
                    "status" :false 
                }
            },
            {
                "id": "p5-c1",
                "parent": "p5",
                "text": "child-1",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "First child under Parent-5",
                    "status" :true 
                }
            }, 
            {
                "id": "p5-c2",
                "parent": "p5",
                "text": "child-2",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "Second child under Parent-5",
                    "status" :false
                }
            },
            {
                "id": "p5-c3",
                "parent": "p5",
                "text": "child-3",
                "type":"child",
                "li_attr" : { "class" : "child" },
                "data": { 
                    "description": "third child under Parent-5",
                    "status" :true 
                }
            }
                





                /*
                , {
                    "id": "c3",
                    "parent": "p2",
                    "text": "child-3"
                }, {
                    "id": "c4",
                    "parent": "p2",
                    "text": "child-4"
                }

                , {
                    "id": "c5",
                    "parent": "p2",
                    "text": "child-5"
                }, {
                    "id": "c6",
                    "parent": "p2",
                    "text": "child-6"
                }
                 //
                , {
                    "id": "c1",
                    "parent": "p1",
                    "text": "child-1"
                }, {
                    "id": "c2",
                    "parent": "p1",
                    "text": "child-2"
                }

                , {
                    "id": "c3",
                    "parent": "p1",
                    "text": "child-3"
                }, {
                    "id": "c4",
                    "parent": "p1",
                    "text": "child-4"
                }

                , {
                    "id": "c5",
                    "parent": "p1",
                    "text": "child-5"
                }, {
                    "id": "c6",
                    "parent": "p1",
                    "text": "child-6"
                }
                */
        ];

        //data =[];






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
            var level=data.node.parents.length;
            console.log(level);

        }).on('select_node.jstree', function(event, data){

            console.log(data.selected[0]);
            //console.log(data.selected[0].text());
            console.log( $('#'+data.node.id).attr('aria-level') );


            var level = data.node.parents.length;
            var text  = data.node.text;
            var id    = data.node.id;
            console.log(level,text,id);


            $('#desig-name').val(text);

            $('#desig-name').attr('data-source',id); //setter




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
            for(var i=0;i<childrens.length;i++)
            {
                console.log('~~~' + $('#'+childrens[i]).text());
            }

        });


            //update designation using form
        $(document).on("click","#desig-update-btn",function() {
            var desigName = $('#desig-name').val();

                var sourceid  = $('#desig-name').attr('data-source'); //setter

                alert(desigName);
                alert(sourceid);

                //$("#designation_jstree").jstree('set_text', '#' + sourceid , desigName );
                $("#designation_jstree").jstree('rename_node', '#' + sourceid , desigName );
                //$("#demo1").jstree('rename_node', node , text );
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
//
//
            var k=0;

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






    function create_parent(parentName) {
        let ref = $('#designation_jstree').jstree(true);


        //get all parent nodes
        let node_info = $("#designation_jstree").jstree("get_node",'#');
        let parentnodeArrById = node_info.children;



        // Extract numbers and find the maximum
        let numbers = parentnodeArrById.map(item => parseInt(item.substring(1))); // [1, 2, 3, 4, 5, 8, 6]
        let biggestId = numbers.length > 0 ? Math.max(...numbers) : 0;
        console.log(biggestId);


        function  checkUniqueId(id){

            let tempId = "p" + id;
            for (let i = 0; i < parentnodeArrById.length; i++)
            {
                let loopitemId = parentnodeArrById[i];
                if (tempId === loopitemId)
                {
                    return false;
                }
            }
            return id;
        }

        function  checkUniqueName(id){

            //var tempNodeText = "Parent-"+ id;
            let tempNodeText = parentName + "-"+ id;
            //let exists = arr.includes(gg);

            //$('#designation_jstree ')
            let tree = $('#designation_jstree').jstree(true);

            // Get all nodes
            let allNodes = tree.get_json('#', {flat: true});


            // Assuming your array is called something like 'nodes' or 'data'
            let parentObjects = allNodes.filter(obj => obj.type === 'parent');

            let parentTxtArr = parentObjects.map(obj => obj.text);
            let isUnique = parentTxtArr.length > 0 ? !parentTxtArr.includes(tempNodeText) : true;



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

        //let isUniqueId   = false;
        let isUniqueName = false;

        //while( isUniqueId ===false || isUniqueName ===false ){
        while( isUniqueName === false ){
            biggestId++;
            //isUniqueId   = checkUniqueId(biggestId);
            isUniqueName = checkUniqueName(biggestId);

            //todo - if not unique - alert and close


        }


        alert(biggestId);

        let sel = ref.create_node('#',  {
            "id": "p" + biggestId,
                                            //"text": "Parent-"+ k,
            "text": parentName + "-"+ biggestId,

            "type" : "parent",
            "li_attr" : { "class" : "parent" },
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
        resetForm();
    }

    function resetForm() {
        $('#desig-name').val('');
        $('#desig-desc').val('');
        $('#desig-name').attr('data-source', '');
    }










    function resetModalForm() {
        $('#modal-desig-name').val('');
        $('#modal-desig-desc').val('');
        $('#modal-desig-status').val('1');
        $('#modal-desig-name').attr('data-source', ''); //todo
    }


    // Open modal button handler
    $(document).on("click", "#btnOpenDetailsModal", function() {
        // Open the modal
        $('#designationDetailsModal').modal('show');
    });

    // Modal update button handler
    $(document).on("click", "#modal-desig-update-btn", function() {
        var desigName = $('#modal-desig-name').val();
        var desigDesc = $('#modal-desig-desc').val();
        var desigStatus = $('#modal-desig-status').val();
        var sourceid = $('#modal-desig-name').attr('data-source');

        if(!sourceid) {
            alert('Please select a designation from the tree first');
            return false;
        }

        alert('Updating: ' + desigName);

        // Update the tree node
        $("#designation_jstree").jstree('rename_node', '#' + sourceid, desigName);

        // Also update the main form
        $('#desig-name').val(desigName);
        $('#desig-desc').val(desigDesc);
        $('#desig-status').val(desigStatus);

        // Close the modal
        $('#designationDetailsModal').modal('hide');
    });


</script>
@stop


