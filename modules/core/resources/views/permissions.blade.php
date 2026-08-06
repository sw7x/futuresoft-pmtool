@extends('core-module::layouts.master',['title' => 'Permissions'])
@section('title','Permissions')


@section('css-files')
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('plugins/jstree/dist/themes/default/style.min.css')}}" />    
    <link rel="stylesheet" href="{{ asset('css/plugins/sweetalert/sweetalert.css')}}" />    
    <link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css')}}" />
@stop


@section('page-css')
    <style>
        /* For all jsTree nodes */
        #jstree_permissions .jstree-node .jstree-anchor {
            max-width: calc(100% - 20px);
            /* max-width: 450px;  Set your desired max width */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }

        /* To show long text in jsTree with ellipsis (...)*/
        #jstree_permissions  ul.jstree-children{
            display: block;
        }


        .node-highlight-uncheck > a {
            transition: all 0.4s ease;
            background-color: #F44336 !important;
            border-radius: 4px;
            color: #ffffff !important;
            font-weight: bold !important;        
        }

        .node-highlight-check > a {
            transition: all 0.4s ease;
            background-color: #20c997 !important;
            border-radius: 4px;
            color: #ffffff !important;
            font-weight: bold !important;        
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

                    <div class="form-group row mb-4">
                        <label class="offset-4 col-sm-2 col-form-label font-bold text-lg">Select Role</label>
                        <div class="col-sm-3">
                            <select class="form-control m-b" name="role_id" id="role_select">
                                <option value=""></option>
                                @foreach($roleArr as $role)
                                <option data-roleName='{{$role['name']}}' value="{{ $role['id'] }}" {{ $role['id'] == $selRoleId ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $role['name'])) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-block" id='btn_load_permissions'>Load</button>    
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-7">
                            <div class="card mb-3 h-100">

                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-sitemap mr-2"></i> Permissions Tree
                                    </h4>
                                </div>

                                <div id="" class="card-body box-container border-bottom overflow-hidden px-2">
                                    <div id="jstree_permissions" class=""></div>
                                    <!-- Hidden container for selected permissions to be submitted
                                        <div id="selected_permissions_container"></div> 
                                    -->
                                </div>



                                <div class="card-footer bg-white border-top-0">
                                    <div class="p-0">
                                        <button type="button" class="text-left btn bg-blue-200 _btn-primary btn-block mb-2 shadow-sm" id="btnCreateParent">
                                            <i class="fa fa-plus-circle mr-2"></i> Create main
                                        </button>
                                        <button type="button" class="text-left btn btn-info btn-block mb-2 shadow-sm" id="btnCreateSub">
                                            <i class="fa fa-code-fork mr-2"></i> Create sub
                                        </button>
                                        <button type="button" class="text-left btn btn-danger btn-md btn-block shadow-sm" id="btnDelete">
                                            <i class="fa fa-trash mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top">
                                    <div class="p-0">
                                        <button type="button" id="permission-reset-btn" class="text-left reset btn btn-block btn-warning flex-fill shadow-sm font-semibold">
                                            <i class="fa fa-refresh mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>                      

                        <div class="col-md-5">
                            <div class="card mb-3 h-100">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="card-title m-0 font-semibold">
                                        <i class="fa fa-address-card-o mr-2"></i> Permission Details
                                    </h4>
                                </div>

                                <div id="permission-display" class="p-2 card-body box-container">
                                    <div class="mb-4">
                                        <label class="text-muted small font-bold d-block mb-1 uppercase tracking-wider">Permission Name</label>
                                        <div class="bg-gray-100 p-2 rounded border">
                                            <code id="perm_name" class="text-primary font-bold"></code>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-muted small font-bold d-block mb-1 uppercase tracking-wider">Identifier Key</label>
                                        <div class="bg-gray-100 p-2 rounded border">
                                            <code id="perm_key" class="text-primary font-bold text-lg"></code>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-muted small font-bold d-block mb-1 uppercase tracking-wider">Current Access</label>
                                        <div class="bg-gray-100 p-2 rounded border">
                                            <code id="perm_access" class="text-info font-bold text-lg"></code>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="text-muted small font-bold d-block mb-1 uppercase tracking-wider">Description</label>
                                        <p id="display-desc" class="text-muted font-italic border-left pl-3 py-1">Select a permission to view its details.</p>
                                    </div>
                                </div>
                                    
                                <div class="card-footer bg-white border-top"> 
                                    <div class="">
                                        <button type="button" id="permission-update-btn" class="text-left btn btn-block btn-primary flex-fill shadow-sm font-semibold">
                                            <i class="fa fa-save mr-1"></i> Update Permissions
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

    @php
        //echo '<pre>' . json_encode($permissions, JSON_PRETTY_PRINT) . '</pre>';
    @endphp

@stop


@section('bootstrap-modals')
    <!-- Permission Details Modal -->
    <div class="modal fade" id="permission-create-modal" tabindex="-1" role="dialog" aria-labelledby="permission-create-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h3 class="modal-title" id="permission-create-modalLabel">
                        <i class="fa fa-plus-circle mr-2"></i> Create Permission
                    </h3>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal-permission-create-form" action="">
                        <div class="form-group role-info-div">
                            <label>Designated Role</label>
                            <div class="bg-gray-100 px-2 py-1 rounded border">
                                <code class="text-primary font-bold text-lg modal-role-name"></code>
                            </div>
                        </div>
                        
                        <div class="form-group parent-info-div">
                            <label for="modal-parent-permission-name">Parent Permission</label>
                            <input type="text" class="form-control parent-permission-name" value="" disabled>
                        </div>

                        <div class="form-group">
                            <label for="modal-permission-name">Permission Name</label>
                            <input type="text" class="form-control" id="modal-permission-name" data-source="">
                        </div>

                        <div class="form-group">
                            <label class="text-muted small font-bold d-block mb-1 uppercase tracking-wider">Identifier Key</label>
                            <div class="bg-gray-100 p-2 rounded border">
                                <code id="modal-permission-key" class="text-primary font-bold text-lg"></code>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="modal-permission-status">Access</label>
                            <select class="form-control" id="modal-permission-access">
                                <option value="true">Allow</option>
                                <option value="false">Deny</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="modal-permission-create-btn" class="btn btn-primary">
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
    <script type="text/javascript" src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('plugins/jstree/dist/jstree.js')}}"></script>
    <script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js')}}"></script>
@stop


@section('javascript')
    <script>
        // Directly use as JavaScript object
        const permissions = @json($permissions);
        //console.log(permissions);        


        $(document).ready(function () {

            $("#role_select").select2({
                placeholder: "-- Choose Role --",
                allowClear: true,
                width: '100%'
            });

            // --- Constants & Selectors ---
            const $tree         = $("#jstree_permissions");
            const $displayForm  = $("#permission-display");
            const $modal        = $("#permission-create-modal");
            const $modalForm    = $("#modal-permission-create-form");


            let initialCheckedNodes = []; // store initial state
            let isChkProgrammatic = false; // ← flag
            
            
            // Initialize jsTree
            $tree.jstree({
                'core' : {
                    "check_callback": true,
                    //"data": data,
                    "data": permissions,
                    "themes": { "stripes": true },
                    "force_text": false,
                    "allow_reselect": true
                },
                'plugins' : [ 'checkbox', 'types' ],
                'checkbox': {

                    'keep_selected_style': true,
                    'three_state': false, // This enables the master/sub hierarchy logic
                    //'cascade': 'up+down',
                    //'cascade': 'up',

                    'whole_node': false, // Prevents clicking on node text from toggling checkbox
                    'tie_selection': false // Prevents checkbox from affecting node selection
                },
                'types' : {
                    /*
                    'root':     {'icon': 'fa fa-database text-success'},
                    'branch':   {'icon': 'fa fa-folder text-info'},
                    'twig':     {'icon': 'fa fa-file text-warning'},
                    'leaf':     {'icon': 'fa fa-key text-danger'}
                    */

                    'root':     {'icon': 'fa fa-key text-danger border-1 border-red-400 mr-1'},
                    'branch':   {'icon': 'fa fa-key text-warning border-1 border-yellow-400 mr-1'},
                    'twig':     {'icon': 'fa fa-key text-success border-1 border-blue-400 mr-1'},
                    'leaf':     {'icon': 'fa fa-key text-info border-1 border-green-400 mr-1'}
                }
            }).on('ready.jstree', function() {
                console.log("Tree ready");

                // ✅ Save initial checked nodes on page load
                initialCheckedNodes = $tree.jstree(true).get_checked();
                console.log("Initial checked nodes:", initialCheckedNodes);

            }).on('select_node.jstree', function(event, data) {        
                console.log(data.node.text);
                populateForm(data.node);
            });


            /*
             * ============================================================
             * PERMISSION TREE — CHECKBOX STATE RULES
             * ============================================================
             *
             * Each node in the tree represents a user permission.
             * Parent nodes act as permission groups.
             * Child nodes act as individual permissions within that group.
             *
             * LEGEND:
             *   ✔️  — Permission ALLOWED
             *   ❌  — Permission DENIED
             *
             * ------------------------------------------------------------
             * RULE 1: Parent DENIED — Children can be ANY state
             *         When parent is denied, child states do not matter.
             *         The whole group is considered denied.
             * ------------------------------------------------------------
             *
             *  ✅ VALID                ✅ VALID
             *  parent       ❌         parent      ❌
             *    ├── child  ❌           ├── child  ❌
             *    ├── child  ❌           ├── child  ✔️
             *    └── child  ❌           └── child  ❌
             *
             * ------------------------------------------------------------
             * RULE 2: Parent ALLOWED — ALL children MUST be allowed
             *         If parent is allowed, every child must also be
             *         allowed. A mix of allowed/denied children under
             *         an allowed parent is considered INVALID.
             * ------------------------------------------------------------
             *
             *  ✅ VALID                ❌ INVALID              ❌ INVALID
             *  parent       ✔️         parent      ✔️          parent      ✔️
             *    ├── child  ✔️           ├── child  ✔️            ├── child  ❌
             *    ├── child  ✔️           ├── child  ❌            ├── child  ❌
             *    └── child  ✔️           └── child  ✔️            └── child  ❌
             *
             * ------------------------------------------------------------
             * SUMMARY TABLE:
             * ------------------------------------------------------------
             *
             *   Parent  │  Children       │  Valid?
             *  ─────────┼─────────────────┼─────────
             *     ❌    │  All ❌        │  ✅ Yes
             *     ❌    │  Mix ❌/✔️     │  ✅ Yes
             *     ✔️    │  All ✔️        │  ✅ Yes
             *     ✔️    │  Mix ✔️/❌     │  ❌ No
             *     ✔️    │  All ❌        │  ❌ No
             *
             * ------------------------------------------------------------
             * NOTE:
             *   - Allowing a parent without allowing all children
             *     creates an inconsistent permission state.
             *   - Always validate the tree before saving permissions.
             * ============================================================
             */

            
            /*when checkbox checked this event fired*/
            $tree.on('check_node.jstree', function(e, data) {
                //when #permission-reset-btn clicked this is executed
                //alert('check : ' + data.node.id + ' parent : ' + data.node.parent);
               
               console.log('===check_node===');

                /*
                 * ============================================================
                 * PERMISSION TREE — AUTO VALIDATION ON PARENT CHECK
                 * ============================================================
                 *
                 * When a user checks a parent permission node, the tree may
                 * enter an INVALID state (allowed parent with denied children).
                 * The system automatically detects and fixes this by allowing
                 * all children nodes to restore a valid permission tree state.
                 *
                 * TRIGGER  : User checks a parent node
                 * DETECTION: Parent is ✔️ ALLOWED but has at least one ❌ DENIED child
                 * FIX      : System automatically sets ALL children to ✔️ ALLOWED
                 *
                 * ============================================================
                 * SCENARIO 1 — User checks parent when ALL children are denied
                 * ============================================================
                 *
                 *   VALID (before)          INVALID (user action)        VALID (system fix)
                 *  ─────────────────────   ──────────────────────────   ───────────────────────
                 *   parent       ❌         parent       ✔️  ← checked   parent       ✔️
                 *     ├── child  ❌           ├── child  ❌                ├── child  ✔️  ← fixed
                 *     ├── child  ❌           ├── child  ❌                ├── child  ✔️  ← fixed
                 *     └── child  ❌           └── child  ❌                └── child  ✔️  ← fixed
                 *
                 *  REASON: Parent became ✔️ ALLOWED but all children were ❌ DENIED.
                 *          System allows all children to fix the invalid state.
                 *
                 * ============================================================
                 * SCENARIO 2 — User checks parent when SOME children are denied
                 * ============================================================
                 *
                 *   VALID (before)          INVALID (user action)        VALID (system fix)
                 *  ─────────────────────   ──────────────────────────   ───────────────────────
                 *   parent       ❌         parent       ✔️  ← checked   parent       ✔️
                 *     ├── child  ✔️           ├── child  ✔️                ├── child  ✔️
                 *     ├── child  ❌           ├── child  ❌                ├── child  ✔️  ← fixed
                 *     └── child  ✔️           └── child  ✔️                └── child  ✔️
                 *
                 *  REASON: Parent became ✔️ ALLOWED but one child was ❌ DENIED.
                 *          System allows the denied child to fix the invalid state.
                 *
                 * ============================================================
                 * VALIDATION LOGIC SUMMARY:
                 * ============================================================
                 *
                 *   Step 1 : User checks the parent node
                 *   Step 2 : System checks all children states
                 *   Step 3 : If at least one child is ❌ DENIED → fix children
                 *   Step 4 : Set ALL children to ✔️ ALLOWED (tree is now valid)
                 *
                 * ============================================================
                 * AFFECTED STATES:
                 * ============================================================
                 *
                 *   Parent  │  Children       │  Valid?  │  Action
                 *  ─────────┼─────────────────┼──────────┼───────────────────────────
                 *     ✔️    │  All ✔️         │  ✅ Yes  │  No action needed
                 *     ✔️    │  Mix ✔️/❌      │  ❌ No   │  Set all children to ✔️
                 *     ✔️    │  All ❌         │  ❌ No   │  Set all children to ✔️
                 *     ❌    │  Any state      │  ✅ Yes  │  No action needed
                 *
                 * ============================================================
                 */

                
                // When checking programmatically, skip validation checks
                if(isChkProgrammatic) return;

                const ref = $tree.jstree(true);            
                const nowCheckedLevel = data.node.parents.length;

                // Get all nested children (all levels)
                const allChildren = data.node.children_d;
                //console.log("All children IDs:", allChildren);

                // expand the checked node itself
                ref.open_node(data.node); 

                
                for (let i = 0; i < allChildren.length; i++) {
                    (function(index) {
                        const childNode = ref.get_node(allChildren[index]);
                        //let treeLevel = childNode.children.length;
                        const relativeDepth = childNode.parents.length - nowCheckedLevel;

                        const delay     = (relativeDepth + 1) * 600; // for sequential delays

                        if (childNode.children.length > 0) {
                            ref.open_node(childNode);
                        }

                        if(!ref.is_checked(childNode)){
                            let childLi  = $('#' + allChildren[index]);  
                            childLi.removeClass('node-highlight-uncheck');              
                            childLi.addClass('node-highlight-check');
                            //childLi.addClass('delay-'+delay);
                            //childLi.addClass('treeLevel-'+treeLevel);  
                        }
                        
                        setTimeout(function() {
                            if(!ref.is_checked(allChildren[index])){
                                ref.check_node(allChildren[index]);
                                console.log(allChildren[index]);    
                                console.log($('#' + allChildren[index])); 
                                $('#' + allChildren[index]).removeClass('node-highlight-check');
                            }
                        }, delay);

                    })(i);
                }            
            });


            /*when checkbox un-checked this event fired*/
            $tree.on('uncheck_node.jstree', function(e, data) {
                //when #permission-reset-btn clicked this is not executed
                //when already checkbox uncheked and programically uncheck it then this will not execute

                console.log('===uncheck_node===');
                
                /*
                 * ============================================================
                 * PERMISSION TREE — AUTO VALIDATION ON CHILD UNCHECK
                 * ============================================================
                 *
                 * When a user unchecks a child permission node, the tree may
                 * enter an INVALID state (allowed parent with denied children).
                 * The system automatically detects and fixes this by denying
                 * the parent node to restore a valid permission tree state.
                 *
                 * TRIGGER  : User unchecks a child node
                 * DETECTION: Parent is ✔️ ALLOWED but has at least one ❌ DENIED child
                 * FIX      : System automatically sets parent to ❌ DENIED
                 *
                 * ============================================================
                 * SCENARIO 1 — User unchecks ONE child out of ALL allowed
                 * ============================================================
                 *
                 *   VALID (before)          INVALID (user action)       VALID (system fix)
                 *  ─────────────────────   ─────────────────────────   ──────────────────────
                 *   parent       ✔️         parent       ✔️              parent       ❌  ← fixed
                 *     ├── child  ✔️           ├── child  ✔️                ├── child  ✔️
                 *     ├── child  ✔️           ├── child  ❌  ← unchecked   ├── child  ❌
                 *     └── child  ✔️           └── child  ✔️                └── child  ✔️
                 *
                 *  REASON: Parent was ✔️ ALLOWED but one child became ❌ DENIED.
                 *          System denies the parent to fix the invalid state.
                 *
                 * ============================================================
                 * SCENARIO 2 — User unchecks LAST allowed child
                 *              (all children become denied)
                 * ============================================================
                 *
                 *   VALID (before)          INVALID (user action)       VALID (system fix)
                 *  ─────────────────────   ─────────────────────────   ──────────────────────
                 *   parent       ✔️         parent       ✔️              parent       ❌  ← fixed
                 *     ├── child  ❌           ├── child  ❌                ├── child  ❌
                 *     ├── child  ❌           ├── child  ❌                ├── child  ❌
                 *     └── child  ✔️           └── child  ❌  ← unchecked   └── child  ❌
                 *
                 *  REASON: Parent was ✔️ ALLOWED but all children became ❌ DENIED.
                 *          System denies the parent to fix the invalid state.
                 *
                 * ============================================================
                 * VALIDATION LOGIC SUMMARY:
                 * ============================================================
                 *
                 *   Step 1 : User unchecks a child node
                 *   Step 2 : System checks the parent state
                 *   Step 3 : If parent is ✔️ ALLOWED → check all children
                 *   Step 4 : If at least one child is ❌ DENIED → fix parent
                 *   Step 5 : Set parent to ❌ DENIED  (tree is now valid)
                 *
                 * ============================================================
                 * AFFECTED STATES:
                 * ============================================================
                 *
                 *   Parent  │  Children       │  Valid?  │  Action
                 *  ─────────┼─────────────────┼──────────┼──────────────────────
                 *     ✔️    │  All ✔️         │  ✅ Yes  │  No action needed
                 *     ✔️    │  Mix ✔️/❌      │  ❌ No   │  Set parent to ❌
                 *     ✔️    │  All ❌         │  ❌ No   │  Set parent to ❌
                 *     ❌    │  Any state      │  ✅ Yes  │  No action needed
                 *
                 * ============================================================
                 */         

                const ref = $tree.jstree(true);
                const parentId   = data.node.parent;

                let parents = [];
                let tempParent = parentId;

                while (tempParent && tempParent !== '#') {
                    parents.push(tempParent);
                    tempParent = ref.get_node(tempParent).parent; // ← move up
                }
                //console.log(parents);
                
                // Process each with delay
                for (let i = 0; i < parents.length; i++) {
                    (function(index) {
                        const delay     = (index + 1) * 750; // Multiply by index+1 for sequential delays
                        
                        const parentLi  = $('#' + parents[index]);                    
                        parentLi.removeClass('node-highlight-check');
                        parentLi.addClass('node-highlight-uncheck');

                        setTimeout(function() {

                            parentLi.removeClass('node-highlight-uncheck');
                            if(ref.is_checked(parents[index]))
                                ref.uncheck_node(parents[index]); 

                        }, delay); 
                    })(i);
                }
            });


            /**
             * Fills the update form with selected node data
             */
            function populateForm(node) {
                const id = node.id;
                const $li = $("#" + id);

                const key       = $li.data('key') || '';
                const status    = $li.data('access') === false ? 'Deny' : 'Allow';
                const addStatusCls      = $li.data('access') === false ? 'text-danger' : 'text-info';
                const removeStatusCls   = $li.data('access') === false ? 'text-info' : 'text-danger';

                $displayForm.find('#perm_name').text(node.text);
                $displayForm.find('#perm_key').text(key);

                $displayForm.find('#perm_access').addClass(addStatusCls).removeClass(removeStatusCls);
                $displayForm.find('#perm_access').text(status);

                $tree.jstree("open_node", $li);
            }


            /**
             * Generates a unique ID for new nodes
             */
            function generateNodeId(parentId, type) {
                const ref = $tree.jstree(true);
                const children = parentId === '#' ? ref.get_node('#').children : ref.get_node(parentId).children;
                
                if (type === 'root') {
                    const numbers = children.map(id => parseInt(id.substring(4)) || 0);
                    const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
                    return "root" + (biggest + 1);

                } else if (type === 'branch') {
                    const numbers = children.map(id => (id.includes('-branch') ? parseInt(id.split('-branch')[1]) : 0));
                    const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
                    return parentId + "-branch" + (biggest + 1);

                } else if (type === 'twig') {
                    const numbers = children.map(id => (id.includes('-twig') ? parseInt(id.split('-twig')[1]) : 0));
                    const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
                    return parentId + "-twig" + (biggest + 1);

                } else if (type === 'leaf') {
                    const numbers = children.map(id => (id.includes('-leaf') ? parseInt(id.split('-leaf')[1]) : 0));
                    const biggest = numbers.length > 0 ? Math.max(...numbers) : 0;
                    return parentId + "-leaf" + (biggest + 1);

                }else{
                    return swal("Oops", "Invalid node type", "error");
                }            
            }


            /**
             * Checks if a permission name is unique within its context
             */
            function isNameUnique(name, parentId) {
                const ref = $tree.jstree(true);
                const siblings = (parentId === '#') 
                ? ref.get_json('#', { flat: true }).filter(n => n.parent === '#' && n.type === 'root')
                : ref.get_node(parentId).children.map(id => ref.get_node(id));

                const names = siblings.map(node => (node.text || '').toLowerCase());
                return !names.includes(name.toLowerCase());
            }

            window.create_parent = function(name, key = '', access = true) {
                if (!name) return swal("Oops", "Permission cannot be empty", "error");
                if (!isNameUnique(name, '#')) return swal("Oops", "Main permission already exists", "error");
                
                /*
                const ref = $tree.jstree(true);
                const newId = generateNodeId('#', 'root');
                
                const sel = ref.create_node('#', {
                    "id": newId, "text": name, "type": "root",
                    "li_attr": { "class": "root", "data-key": key, "data-access": access }
                }, "last");
                                             
                if (sel) {
                    ref.deselect_all();
                    ref.select_node(sel);
                    ref.edit(sel);
                }
                */ 

                const rootDbRec = {
                    name      : name,
                    key       : key || name.toUpperCase().replace(/\s+/g, '_'),
                    parent_id : '',
                    access    : (access === true)?'allow':'deny',
                    role_id   : parseInt($('#role_select').val(), 10),
                    status    : true,
                    _token    : '{{ csrf_token() }}'
                };

                // Create a hidden form and submit it
                const $form = $('<form>', {
                    action: "{{ route('permissions.store') }}",
                    method: "POST"
                });

                $.each(rootDbRec, function(k, v) {
                    $form.append($('<input>', {
                        type: 'hidden',
                        name: k,
                        value: v
                    }));
                });

                $('body').append($form);
                $form.submit();
            };


            window.create_sub = function(name, key = '', access = true) {
                if (!name) return swal("Oops", "Permission(sub) cannot be empty", "error");

                const ref = $tree.jstree(true);
                const selParent = ref.get_selected();
                if (!selParent.length) return swal("Oops", "Please select a parent permission", "error");

                const parentId = selParent[0];
                if (!isNameUnique(name, parentId)) return swal("Oops", "Permission already exists under this parent", "error");

                
                const selectedNode  = ref.get_node(parentId);
                const nodeType      = selectedNode.type; // "root" for your example


                if(nodeType == 'root'){
                    childNodeType = 'branch';
                }else if(nodeType == 'branch'){
                    childNodeType = 'twig';
                }else if(nodeType == 'twig'){
                    childNodeType = 'leaf';
                }else{
                    return swal("Oops", "Invalid node type", "error");
                }

                /*
                const newId = generateNodeId(parentId, childNodeType);

                const sel = ref.create_node(parentId, {
                    "id": newId, "text": name, "type": childNodeType,
                    "li_attr": { "class": "child", "data-key": key, "data-access": access }
                }, "last");

               
                if (sel) {
                    ref.deselect_all();
                    ref.select_node(sel);
                    ref.open_node(parentId);
                    ref.edit(sel);
                }
                */

                const subDbRec = {
                    name      : name,
                    key       : key || name.toUpperCase().replace(/\s+/g, '_'),
                    parent_id : $("#" + selectedNode.id).data('db_rec_id'),
                    access    : (access === true)?'allow':'deny',
                    role_id   : parseInt($('#role_select').val(), 10),
                    status    : true,
                    _token    : '{{ csrf_token() }}'
                };

                // Create a hidden form and submit it
                const $form = $('<form>', {
                    action: "{{ route('permissions.store') }}",
                    method: "POST"
                });

                $.each(subDbRec, function(k, v) {
                    $form.append($('<input>', {
                        type: 'hidden',
                        name: k,
                        value: v
                    }));
                });

                $('body').append($form);
                $form.submit();
            };


            // Delete Node
            $("#btnDelete").on("click", function() {
                const ref = $tree.jstree(true);
                const sel = ref.get_selected();
                if (!sel.length) swal("Oops!", "Please select a permission to delete", "error");;

                const node = ref.get_node(sel[0]);
                const childCount = node.children.length;

                parent_id = $("#" + node.id).data('db_rec_id');

                const delDbRecInfo = {
                    role_id : parseInt($('#role_select').val(), 10),
                    _token  : '{{ csrf_token() }}',
                    _method: 'DELETE'
                };

                // Create a hidden form and submit it
                const $form = $('<form>', {
                    action: "{{ route('permissions.destroy', ['id' => ':id']) }}".replace(':id', parent_id),
                    method: "POST"
                });

                $.each(delDbRecInfo, function(k, v) {
                    $form.append($('<input>', {
                        type: 'hidden',
                        name: k,
                        value: v
                    }));
                });

                
                if (childCount > 0) {
                    swal({
                        title: "Warning",
                        text: `Permission has ${childCount} child permission(s). Delete everything?`,
                        type: "warning",  // Use "type" instead of "icon" in v1
                        showCancelButton: true,
                        confirmButtonText: "Delete All",
                        cancelButtonText: "Abort",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $('body').append($form);
                            $form.submit();
                            //swal("Deleted!", "Permission and its children removed.", "success");
                        }
                    });
                } else {
                    $('body').append($form);
                    $form.submit();
                }
                
            });

            
            // Reset Modal Form Helper
            function resetModalForm() {
                console.log($modalForm);
                $modalForm.find('input[type="text"], textarea, select').not('.parent-permission-name').val('');
                $modalForm.find('select').val('true');        
            }

            // Reset Modal Form Listener
            $(document).on("click", "#btnResetModal", function() {
                //$modalForm[0].reset();
                resetModalForm();
                $('#modal-permission-key').text(''); // Clear the key display
            });

            // Auto-generate Identifier Key from Permission Name
            $(document).on('input', '#modal-permission-name', function() {
                const name = $(this).val();
                const key = name.toUpperCase().replace(/\s+/g, '_');
                $('#modal-permission-key').text(key);
            });


            // Modal: Open Create Main
            $(document).on("click", "#btnCreateParent", function() {
                resetModalForm();

                // Get selected role info
                const roleName = $('#role_select option:selected').text();
                const roleVal = $('#role_select').val();
                if (roleVal) {
                    $('.modal-role-name').text(roleName.trim());
                } else {
                    $('.modal-role-name').text('N/A (No Role Selected)');
                }

                $('#modal-permission-key').text('');
                $modalForm.find('#modal-permission-name').removeData('source').removeAttr('data-source');
                $modal.find('.parent-permission-name').val('');

                $modal.find('.parent-info-div').hide();
                $modal.modal('show');
            });

            
            // Modal: Open Create Sub
            $(document).on("click", "#btnCreateSub", function() {
                const ref = $tree.jstree(true);
                const sel = ref.get_selected();

                if (!sel.length) return swal("Oops", "Please select a parent permission first", "info");

                const node = ref.get_node(sel[0]);
                const depth = $('#' + node.id + ' > a').attr('aria-level');
                if (depth > 3) return swal("Oops", "Nesting limit is 4 levels", "warning");


                let parentsArr = [...node.parents].reverse().slice(1);
                
                let breadcrumTxt ='';
                parentsArr.forEach((item, index) => {
                    let parentName = ref.get_node(item).text
                    breadcrumTxt = index == 0 ? parentName : `${breadcrumTxt} ➤ ${parentName}`                
                });               

                let parentHierarchy = parentsArr.length == 0 ? node.text : `${breadcrumTxt} ➤ ${node.text}`;

                resetModalForm();

                // Get selected role info
                const roleName = $('#role_select option:selected').text();
                const roleVal = $('#role_select').val();
                
                if (roleVal) {
                    $('.modal-role-name').text(roleName.trim());
                } else {
                    $('.modal-role-name').text('N/A (No Role Selected)');
                }

                $('#modal-permission-key').text('');
                $modal.find('#modal-permission-name').attr('data-source', node.id).data('source', node.id);
                $modal.find('.parent-permission-name').val(parentHierarchy);
                $modal.find('.parent-info-div').show();
                $modal.modal('show');
            });

            
            // Modal: Confirm Create
            $(document).on("click", "#modal-permission-create-btn", function() {
                const name      = $('#modal-permission-name').val();
                const key       = $('#modal-permission-key').text();
                const access    = ($('#modal-permission-access').val() === 'true');
                const parentId  = $('#modal-permission-name').data('source');

                if (typeof parentId === 'undefined') {
                    create_parent(name, key, access);
                } else {
                    create_sub(name, key, access);
                }
                $modal.modal('hide');
            });


            $(document).on("click", "#permission-reset-btn", function() {
                swal({
                    title: "Confirm Reset?",
                    text: "Are you sure you want to revert all permission checkboxes to their initial state?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, reset it!",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        isChkProgrammatic = true;

                        var tree = $tree.jstree(true);
                        // Uncheck all nodes
                        tree.uncheck_all();
                        // Restore initial checked nodes
                        if (initialCheckedNodes.length > 0) {
                            tree.check_node(initialCheckedNodes);
                        }

                        swal("Reset!", "All checkboxes have been reverted to their initial state.", "success");
                        isChkProgrammatic = false;
                    }
                });
            });


            $(document).on("click", "#permission-update-btn", function() {
                const ref = $tree.jstree(true);

                var allNodes = $tree.jstree(true).get_json('#', { flat: true });

                //initialCheckedNodes - when page load Filter out checked ones 

                // when page load Filter out not checked ones 
                const initialUnCheckedNodes = allNodes
                    .map(function(node) { return node.id; })
                    .filter(function(id) { return initialCheckedNodes.indexOf(id) === -1; }); 

                // after user done changes Filter out checked ones
                const nowCheckedNodes   = $tree.jstree(true).get_checked();
                
                // after user done changes Filter out not checked ones
                const nowUnCheckedNodes = allNodes
                    .map(function(node) { return node.id; })
                    .filter(function(id) { return nowCheckedNodes.indexOf(id) === -1; });

                //get db rec ids of user unchecked permission nodes (after page load)                         
                const dbUncheckIds  = initialCheckedNodes.filter(item => !nowCheckedNodes.includes(item));
                let updateDenyDbRecs = dbUncheckIds.map(function(id) {         
                    return ref.get_node(id).li_attr['data-db_rec_id']                
                });

                //get db rec ids of user checked permission nodes (after page load)                        
                const dbCheckIds    = initialUnCheckedNodes.filter(item => !nowUnCheckedNodes.includes(item));
                let updateAllowDbRecs = dbCheckIds.map(function(id) {         
                    return ref.get_node(id).li_attr['data-db_rec_id']                
                });


                // Create a hidden form and submit it
                const $form = $('<form>', {
                    action: "{{ route('permissions.update')}}",
                    method: "POST"
                });

                updateDenyDbRecs.forEach(function(value) {
                    $('<input>').attr({'type':'hidden','name':'denyDbRecs[]','value':value}).appendTo($form);
                });

                updateAllowDbRecs.forEach(function(value) {
                    $('<input>').attr({'type': 'hidden','name': 'allowDbRecs[]','value':value}).appendTo($form);
                });

                $('<input>').attr({'type':'hidden','name':'_token','value':'{{ csrf_token()}}'}).appendTo($form);
                $('<input>').attr({'type':'hidden','name':'role_id','value':$('#role_select').val()}).appendTo($form);
                $('body').append($form);
                $form.submit();
            });

            

            $("#btn_load_permissions").on("click", function() {
                const roleId = $("#role_select").val();
                if(!roleId) {
                    return swal("Wait", "Please select a role first", "info");
                }
                var selRoleName = $('#role_select option:selected').data('rolename');
                // Redirects to /permissions/permissions?role=VAL
                window.location.href = "{{ route('permissions.index') }}?role=" + selRoleName;
            });        

        });
    </script>
@stop