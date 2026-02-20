@extends('layouts.master',['title' => '55Client create'])
@section('title','Client create')


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
                    Client create
                    
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

                    
                    
                    <form class="pm-create-form" id="" action="" method="post">
                        
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-address-card"></i> Account Type</h3>
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Role <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" required id="user_role" name="user_role" value="{{ old('user_role') }}">
                                    <option></option>
                                    <option {{ old("user_role") == 'developer' ? "selected":"" }} value="developer">Developer</option>
                                    <option {{ old("user_role") == 'project_manager' ? "selected":"" }} value="project_manager">Project Manager</option>
                                    <option {{ old("user_role") == 'manager' ? "selected":"" }} value="manager">Manager</option>
                                    <option {{ old("user_role") == 'owner' ? "selected":"" }} value="owner">Owner</option>
                                </select>
                                @if ($errors->has('user_role'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('user_role') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>





                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-user-circle-o"></i> Personal Information</h3>
                        <div class="form-group  row">
                            <label class="col-sm-4 col-form-label">First Name <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="fname" class="form-control" required value="{{ old('fname') }}" placeholder="Enter your first name">
                                @if ($errors->has('fname'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('fname') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-sm-4 col-form-label">Last Name <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="lname" class="form-control" required value="{{ old('lname') }}" placeholder="Enter your last name">
                                @if ($errors->has('lname'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('lname') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="qemail" name="email" class="form-control" required value="{{ old('email') }}" placeholder="your@email.com">
                                @if ($errors->has('email'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('email') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-sm-4 col-form-label">Username <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                                <small>Leave blank if you want to auto generate username</small><br>
                                <small>Only aplha numeric charaters allowed (no spaces, no special characters)</small>
                                @if (Session::get('is_pm_usernameFill')=='y' && $errors->has('username'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('username') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Password <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8 password-container">
                                <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                       name="password" maxlength="12" minlength="6" required value="{{ old('password') }}"/>
                                <button type="button" id="btnToggle" class="pw-toggle" style="right: 20px;">
                                    <i id="eyeIcon" class="fa fa-eye"></i>
                                </button>
                                @if ($errors->has('password'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('password') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>


                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-id-card-o"></i> Demographic Information</h3>
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Date of Birth</label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="mm / dd / yyyy">
                                <div class="w-full">
                                    @if ($errors->has('date_of_birth'))
                                        <ul class="mt-1">
                                            @foreach ($errors->get('date_of_birth') as $error)
                                                <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>                                             
                    
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Gender <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" required id="gender" name="gender" value="{{ old('gender') }}">
                                    <option></option>
                                    <option {{ old("gender") == 'male' ? "selected":"" }} value="male">Male</option>
                                    <option {{ old("gender") == 'female' ? "selected":"" }} value="female">Female</option>
                                    <option {{ old("gender") == 'other' ? "selected":"" }} value="other">Other</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('gender') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>                                                      
                    
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter your full address">{{ old('address') }}</textarea>
                            </div>
                        </div>                                                    
                            
                        <div class="form-group  row">
                            <label class="col-sm-4 col-form-label">Phone Number<span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="tel" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="+94 77 123 4567">
                                @if ($errors->has('phone'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('phone') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>                                                
                    
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NIC Number <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nic" class="form-control" placeholder="Enter your NIC number" required value="{{ old('nic') }}">
                            </div>
                        </div>
                                                                               

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-briefcase"></i> Professional Information</h3>
                        
                            
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Hourly Rate</label>
                            <div class="col-sm-8">
                                <input type="number" name="hourly_rate" class="form-control" placeholder="e.g. 25.00" value="{{ old('hourly_rate') }}">
                            </div>
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Monthly Salary</label>
                            <div class="col-sm-8">
                                <input type="number" name="monthly_salary" class="form-control" placeholder="e.g. 5000.00" value="{{ old('monthly_salary') }}">
                            </div>
                        </div>                                                    
                                                                          
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">EPF/ETF Details</label>
                            <div class="col-sm-8">
                                <textarea name="epf_etf" class="form-control" rows="2" placeholder="Enter EPF/ETF account numbers">{{ old('epf_etf') }}</textarea>
                            </div>
                        </div>                                                        
                        

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-graduation-cap"></i> Qualifications & Skills</h3>
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Education qualifications</label>
                            <div class="col-sm-8">
                                <div class="border border-edu">
                                    <textarea rows="3" class="form-control" name="edu_details" placeholder="List your educational qualifications">{{ old('edu_details') }}</textarea>
                                </div>
                            </div>
                        </div>                                                    

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Skills</label>
                            <div class="col-sm-8">
                                <textarea name="skills" class="form-control" rows="3" placeholder="List your key skills">{{ old('skills') }}</textarea>
                                <small class="text-muted">Separate skills with commas</small>
                            </div>
                        </div>                                                    

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-clock-o"></i> Employment Details</h3>
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Account Status <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label> <input {{  old('account_stat') == "enable" ? "checked" : (old('account_stat') =="disable" ? "" : "checked") }}
                                                   type="radio" checked value="enable" name="account_stat"> <i></i> Enable </label>
                                </div>
                                <div class="i-checks">
                                    <label> <input {{  old('account_stat') == "disable" ? "checked" : "" }}
                                                   type="radio" value="disable" name="account_stat"> <i></i> Disable </label>
                                </div>
                            </div>
                        </div>
                    
                        

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Employment Status <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" required id="employment_status" name="employment_status" value="{{ old('employment_status') }}">
                                    <option></option>
                                    <option {{ old("employment_status") == 'pending' ? "selected":"" }} value="pending">Pending</option>
                                    <option {{ old("employment_status") == 'active' ? "selected":"" }} value="active">Active</option>
                                    <option {{ old("employment_status") == 'resigned' ? "selected":"" }} value="resigned">Resigned</option>
                                    <option {{ old("employment_status") == 'terminated' ? "selected":"" }} value="terminated">Terminated</option>
                                </select>
                                @if ($errors->has('employment_status'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('employment_status') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>




                                                                            
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Termination Date</label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control" name="termination_date" placeholder="mm / dd / yyyy">
                            </div>
                        </div>
                   
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Date Joined <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control" name="date_joined" placeholder="mm / dd / yyyy">
                            </div>
                        </div>
                                                                                

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-camera"></i> Profile Picture</h3>

                        <div class="form-group row"><label class="col-sm-4 col-form-label">Profile image</label>
                            <div class="col-sm-8">
                                <input type="file"
                                       class="filepond-img profile_img"
                                       name="profile_img"
                                       accept="image/webp, image/png, image/jpeg, image/gif"
                                       data-max-file-size="1MB"/>
                                <p>Image size : 500x500</p>
                            </div>
                        </div>                                              
                            
                        <div class="hr-line-dashed"></div>


                        {{ csrf_field() }}
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

    /**/
    (function () {
        // We want to preview images, so we need to register the Image Preview plugin
        FilePond.registerPlugin(

            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews dropped images
            FilePondPluginImagePreview,

            FilePondPluginFileValidateType
        );
        // Select the file input and use create() to turn it into a pond
        const pond = FilePond.create(document.querySelector('.profile_img'));

    })();


    








    $(document).ready(function(){

        //var elem = document.querySelector('.ccode-stat');
        //var init = new Switchery(elem);

        //$('[name="pm_edu-details"]').summernote();

        $('[name="edu_details"]').summernote({
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
        @if(old('edu_details'))
            $('[name="edu_details"]').summernote('code', '{{old('edu_details')}}');
        @endif




        






        // General Datepicker Initializer for all fields with class 'date'
        {{-- 
        $('.date input').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true,
            endDate: '+0d'
        });
        --}}

        


        $('[name="date_of_birth"]').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true,
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('date_of_birth'))
            $("[name='date_of_birth']").datepicker("update", '{{old('date_of_birth')}}');
        @endif
        

        $('[name="termination_date"]').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true,
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('termination_date'))
            $("[name='termination_date']").datepicker("update", '{{old('termination_date')}}');
        @endif


        $('[name="date_joined"]').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true,
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('date_joined'))
            $("[name='date_joined']").datepicker("update", '{{old('date_joined')}}');
        @endif






        $("#gender").select2({
            placeholder: "Select PM gender",
            allowClear: true,
            width: '100%'
        });


        $("#user_role").select2({
            placeholder: "Select user role",
            allowClear: true,
            width: '100%'
        });


        $("#employment_status").select2({
            placeholder: "Select employment_status",
            allowClear: true,
            width: '100%'
        });
        



    });
</script>
@stop



