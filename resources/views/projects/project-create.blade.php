@extends('layouts.master',['title' => 'Project create'])
@section('title','Project create')





@section('css-files')

    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <!-- bootstrap datapicker -->
    <link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('plugins/summernote-0.8.18/summernote-bs4.css')}}">
    <!-- <link href="css/plugins/summernote/summernote-bs4.css" rel="stylesheet">-->

    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">

    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond-plugin-image-preview.min.css')}}">
    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond.min.css')}}">

@stop


@section('content')
    <div class="row" id="">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">
                    
                    @foreach ($errors->all() as $error)
                        {{-- $error --}}
                    @endforeach                                    

                    @if(Session::has('pm_add_message'))
                        <x-flash-message  
                            :class="Session::get('pm_add_cls', 'flash-info')"  
                            :title="Session::get('pm_add_msgTitle') ?? 'Info!'" 
                            :message="Session::get('pm_add_message') ?? 'Info!'"  
                            :message2="Session::get('pm_add_message2') ?? ''"  
                            :canClose="true" />
                    @endif

                    
                    

                    <form class="pm-create-form" id="project-create-form" action="" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <!-- Project Identity -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-rocket"></i> Project Identity</h3>
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Project Name <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="project_name" class="form-control" required value="{{ old('project_name') }}" placeholder="Enter project name">
                                @if ($errors->has('project_name'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('project_name') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe the project">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <!-- Timeline & Deadlines -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-calendar"></i> Timeline & Deadlines</h3>
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Start Date</label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control" name="start_date" value="{{ old('start_date') }}" placeholder="mm/dd/yyyy">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Planned Delivery Date</label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control" name="planned_delivery_date" value="{{ old('planned_delivery_date') }}" placeholder="mm/dd/yyyy">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Actual Delivery Date</label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                <input type="datetime-local" class="form-control" name="actual_delivery_date" value="{{ old('actual_delivery_date') }}" placeholder="mm/dd/yyyy">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Deadline <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-exclamation-triangle text-danger"></i></span>
                                <input type="datetime-local" class="form-control" name="deadline" required value="{{ old('deadline') }}" placeholder="mm/dd/yyyy">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <!-- Finance & Billing -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-money"></i> Finance & Billing</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Currency</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="currency">
                                    <option></option>
                                    <option {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }} value="USD">USD - US Dollar</option>
                                    <option {{ old('currency') == 'EUR' ? 'selected' : '' }} value="EUR">EUR - Euro</option>
                                    <option {{ old('currency') == 'GBP' ? 'selected' : '' }} value="GBP">GBP - British Pound</option>
                                    <option {{ old('currency') == 'JPY' ? 'selected' : '' }} value="JPY">JPY - Japanese Yen</option>
                                    <option {{ old('currency', 'LKR') == 'LKR' ? 'selected' : '' }} value="LKR">LKR - Sri Lankan Rupee</option>
                                    <option {{ old('currency') == 'AUD' ? 'selected' : '' }} value="AUD">AUD - Australian Dollar</option>
                                    <option {{ old('currency') == 'CAD' ? 'selected' : '' }} value="CAD">CAD - Canadian Dollar</option>
                                    <option {{ old('currency') == 'CHF' ? 'selected' : '' }} value="CHF">CHF - Swiss Franc</option>
                                    <option {{ old('currency') == 'CNY' ? 'selected' : '' }} value="CNY">CNY - Chinese Yuan</option>
                                    <option {{ old('currency') == 'HKD' ? 'selected' : '' }} value="HKD">HKD - Hong Kong Dollar</option>
                                    <option {{ old('currency') == 'NZD' ? 'selected' : '' }} value="NZD">NZD - New Zealand Dollar</option>
                                    <option {{ old('currency') == 'SGD' ? 'selected' : '' }} value="SGD">SGD - Singapore Dollar</option>
                                    <option {{ old('currency') == 'INR' ? 'selected' : '' }} value="INR">INR - Indian Rupee</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Estimated Cost</label>
                            <div class="col-sm-8">
                                <input type="number" min="0" step="0.01" name="estimated_cost" class="form-control" value="{{ old('estimated_cost') }}" placeholder="e.g. 50,000.00">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Actual Cost</label>
                            <div class="col-sm-8">
                                <input type="number" min="0" step="0.01" name="actual_cost" class="form-control" value="{{ old('actual_cost') }}" placeholder="e.g. 45,000.00">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Billing Type</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="billing_type">
                                    <option></option>
                                    <option {{ old('billing_type') == 'fixed_cost' ? 'selected' : '' }} value="fixed_cost">Fixed Cost</option>
                                    <option {{ old('billing_type') == 'time_material' ? 'selected' : '' }} value="time_material">Time & Material</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Payment Status</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="payment_status">
                                    <option></option>
                                    <option {{ old('payment_status') == 'not_invoiced' ? 'selected' : '' }} value="not_invoiced">Not Invoiced</option>
                                    <option {{ old('payment_status') == 'partially_paid' ? 'selected' : '' }} value="partially_paid">Partially Paid</option>
                                    <option {{ old('payment_status') == 'paid' ? 'selected' : '' }} value="paid">Paid</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <!-- Classification & Status -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-tags"></i> Classification & Status</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Locality</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="locality">
                                    <option></option>
                                    <option {{ old('locality') == 'local' ? 'selected' : '' }} value="local">Local </option>
                                    <option {{ old('locality') == 'foreign' ? 'selected' : '' }} value="foreign">Foreign</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Project Type <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="project_type">
                                    <option></option>
                                    <option {{ old('project_type') == 'internal' ? 'selected' : '' }} value="internal">Internal</option>
                                    <option {{ old('project_type') == 'client' ? 'selected' : '' }} value="client">Client</option>
                                    <option {{ old('project_type') == 'rd' ? 'selected' : '' }} value="rd">R&D</option>
                                    <option {{ old('project_type') == 'maintenance' ? 'selected' : '' }} value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Project Category <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="project_category">
                                    <option></option>
                                    <option {{ old('project_category') == 'software' ? 'selected' : '' }} value="software">Software</option>
                                    <option {{ old('project_category') == 'infrastructure' ? 'selected' : '' }} value="infrastructure">Infrastructure</option>
                                    <option {{ old('project_category') == 'marketing' ? 'selected' : '' }} value="marketing">Marketing</option>
                                    <option {{ old('project_category') == 'hr' ? 'selected' : '' }} value="hr">HR</option>
                                    <option {{ old('project_category') == 'other' ? 'selected' : '' }} value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Priority</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="priority">
                                    <option></option>
                                    <option {{ old('priority') == 'critical' ? 'selected' : '' }} value="critical">Critical</option>
                                    <option {{ old('priority') == 'high' ? 'selected' : '' }} value="high">High</option>
                                    <option {{ old('priority') == 'medium' ? 'selected' : '' }} value="medium">Medium</option>
                                    <option {{ old('priority') == 'low' ? 'selected' : '' }} value="low">Low</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Project Status <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label> <input {{  old('project_status') == "enable" ? "checked" : (old('project_status') =="disable" ? "" : "checked") }}
                                                   type="radio" checked value="enable" name="project_status"> <i></i> Enable </label>
                                </div>
                                <div class="i-checks">
                                    <label> <input {{  old('project_status') == "disable" ? "checked" : "" }}
                                                   type="radio" value="disable" name="project_status"> <i></i> Disable </label>
                                </div>
                            </div>
                        </div>





                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Progress</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="progress">
                                    <option {{ old('progress') == 'not_started' ? 'selected' : '' }} value="not_started">Not Started</option>
                                    <option {{ old('progress') == 'in_progress' ? 'selected' : '' }} value="in_progress">In Progress</option>
                                    <option {{ old('progress') == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                                    <option {{ old('progress') == 'blocked' ? 'selected' : '' }} value="blocked">Blocked</option>
                                    <option {{ old('progress') == 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <!-- Management -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-user-circle"></i> Management</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Managed by (PM) <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="managed_pm" required>
                                    <option></option>
                                    <option value="1">PM One</option>
                                    <option value="2">PM Two</option>
                                    <option value="3">PM Three</option>
                                    <option value="4">PM Four</option>
                                    <option value="5">PM Five</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <!-- Documentation -->
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-file-text-o"></i> Documentation</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Project Documentation</label>
                            <div class="col-sm-8">
                                <textarea name="documentation" id="documentation" class="form-control">{{ old('documentation') }}</textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>






                        <!-- Phases Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="font-bold text-lg mb-0"><i class="fa fa-calendar"></i> Project Plan</h3>
                            <button type="button" class="btn btn-primary btn-outline btn-xs" id="add-phase-row">
                                <i class="fa fa-plus"></i> Add Phase
                            </button>
                        </div>

                        <div id="phases-container">
                            <div id="no-phases-msg" class="text-center py-4 bg-light border-dashed rounded mb-4">
                                <p class="text-muted mb-0"><i class="fa fa-info-circle"></i> No phases added yet. Click "Add Phase" to begin.</p>
                            </div>
                        </div>

                        <!-- Phase Template (Hidden) -->
                        <template id="phase-template">
                            <div class="phase-row border-bottom mb-4 pb-3">
                                <div class="text-right mb-2">
                                    <button type="button" class="btn btn-danger btn-outline btn-xs remove-phase-row">
                                        <i class="fa fa-times"></i> Remove
                                    </button>
                                </div>
                                <!-- Phase Title -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phase_name[]" class="form-control" placeholder="Enter phase name">
                                    </div>
                                </div>

                                <!-- Phase Description -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Description</label>
                                    <div class="col-sm-8">
                                        <textarea name="phase_description[]" class="form-control" rows="3" placeholder="Briefly describe the phase"></textarea>
                                    </div>
                                </div>

                                <!-- Designation -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Progress</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2-phase" name="phase_progress[]">
                                            <option></option>
                                            <option value="not_started">Not Started</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="blocked">Blocked</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Scheduled Timeline -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Scheduled Timeline</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="datetime-local" class="form-control" name="phase_scheduled_start[]">
                                                </div>
                                                <small class="text-muted">Start Date</small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="datetime-local" class="form-control" name="phase_scheduled_end[]">
                                                </div>
                                                <small class="text-muted">End Date</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actual Timeline -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Actual Timeline</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                                    <input type="datetime-local" class="form-control" name="phase_actual_start[]">
                                                </div>
                                                <small class="text-muted">Start Date</small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                                    <input type="datetime-local" class="form-control" name="phase_actual_end[]">
                                                </div>
                                                <small class="text-muted">End Date</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        



                        <div class="hr-line-dashed"></div>



















                        <div class="form-group row">
                            <div class="col-sm-4 offset-sm-4">
                                <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                            </div>
                        </div>




                    </form>


                    
                    
                    
                    
                    

                </div>
            </div>


            
            
        </div>
    </div>
@stop




@section('script-files')
    <!-- iCheck -->
    <script src="{{asset('js/plugins/iCheck/icheck.min.js')}}"></script>

    <!-- Select2 -->
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>

    <!-- Data picker -->
    <script src="{{asset('js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>

    <!-- SUMMERNOTE -->
    <!-- <script src="../assets/summernote-0.8.18/summernote-lite.js"></script> -->
    <script src="{{asset('plugins/summernote-0.8.18/summernote-bs4.js')}}"></script>


    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-encode.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-validate-size.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-image-exif-orientation.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-image-preview.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-validate-type.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond.min.js')}}"></script>
@stop


@section('javascript')
<script>
    $(document).ready(function(){

        

        $('[name="documentation"]').summernote({
            //placeholder: 'Hello bootstrap 4',
            tabsize: 2,
            height: 250,
            width: '100%',
            toolbar: [

                ['style', ['style']],
                //['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', [
                    'link',
                    //'picture',
                    //'video',
                    'hr'
                ]
                ],
                ['view', [
                    //'fullscreen',
                    'codeview',
                    'help']
                ]
            ],
        });
        @if(old('documentation'))
            $('[name="documentation"]').summernote('code', '{{old('documentation')}}');
        @endif


        $("[name='currency']").select2({
            placeholder: "Select currency",
            allowClear: true,
            width: '100%'
        }); 


        $("[name='billing_type']").select2({
            placeholder: "Select billing type",
            allowClear: true,
            width: '100%'
        });        

        $("[name='payment_status']").select2({
            placeholder: "Select payment status",
            allowClear: true,
            width: '100%'
        });




        $("[name='locality']").select2({
            placeholder: "Select locality",
            allowClear: true,
            width: '100%'
        });        

        $("[name='project_type']").select2({
            placeholder: "Select project type",
            allowClear: true,
            width: '100%'
        });        

        $("[name='project_category']").select2({
            placeholder: "Select project category",
            allowClear: true,
            width: '100%'
        });        

        $("[name='priority']").select2({
            placeholder: "Select project priority",
            allowClear: true,
            width: '100%'
        });        

        $("[name='progress']").select2({
            placeholder: "Select project progress",
            allowClear: true,
            width: '100%'
        });

        $("[name='managed_pm']").select2({
            placeholder: "Select project managed PM",
            allowClear: true,
            width: '100%'
        });

        



        function initPhaseSelect2(element) {
            element.select2({
                placeholder: "Select phase progress",
                allowClear: true,
                width: '100%'
            });
        }

        initPhaseSelect2($(".select2-phase"));

        $('#add-phase-row').click(function() {
            $('#no-phases-msg').hide();
            
            var template = document.querySelector('#phase-template');
            var clone = document.importNode(template.content, true);
            var newRow = $(clone);

            $('#phases-container').append(newRow);
            initPhaseSelect2($('#phases-container .phase-row').last().find('.select2-phase'));
        });

        $(document).on('click', '.remove-phase-row', function() {
            $(this).closest('.phase-row').fadeOut(300, function() {
                $(this).remove();
                if ($('#phases-container .phase-row').length === 0) {
                    $('#no-phases-msg').fadeIn();
                }
            });
        });




        {{-- 
        $('[name="start_date"]').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true,
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('start_date'))
            $("[name='start_date']").datepicker("update", '{{old('start_date')}}');
        @endif 
        --}}
        


    });
</script>
@stop
