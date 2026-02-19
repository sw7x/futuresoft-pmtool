@extends('layouts.master',['title' => 'View single Users'])
@section('title','View single Users')

@section('css-files')
    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <!-- bootstrap datapicker -->
    <link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/summernote-0.8.18/summernote-bs4.css')}}">
    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond-plugin-image-preview.min.css')}}">
    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond.min.css')}}">
    <style>
        /* Modern Premium Badge System */
        .premium-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 14px;
            font-weight: 600;
            font-size: 11px;
            border-radius: 0px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid transparent;
        }

        .designation-badge-container .premium-badge{
            display: block;
        }
        
        .premium-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        }
        
        .premium-badge i {
            margin-right: 6px;
            font-size: 13px;
        }

        /* Designation Specific Styles */
        .badge-designation-primary {
            background-color: #f0f7ff;
            color: #0056b3;
            border-color: #d0e3ff;
        }
        .badge-designation-info {
            background-color: #f0fbff;
            color: #007094;
            border-color: #ccf0ff;
        }
        .badge-designation-success {
            background-color: #f2fff5;
            color: #1a7f37;
            border-color: #cef5d6;
        }

        /* Employment Status Specific Styles */
        .status-active {
            background-color: #ecfdf3;
            color: #027a48;
            border-color: #abefc6;
        }
        .status-pending {
            background-color: #f9fafb;
            color: #344054;
            border-color: #eaecf0;
        }
        .status-resigned {
            background-color: #fffcf0;
            color: #b54708;
            border-color: #fedf89;
        }
        .status-terminated {
            background-color: #fffbfa;
            color: #b42318;
            border-color: #fee4e2;
        }
    </style>
@stop


@section('content')
    <div class="row" id="">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">
                    
                    @if(Session::has('pm_add_message'))
                        <x-flash-message  
                            :class="Session::get('pm_add_cls', 'flash-info')"  
                            :title="Session::get('pm_add_msgTitle') ?? 'Info!'" 
                            :message="Session::get('pm_add_message') ?? 'Info!'"  
                            :message2="Session::get('pm_add_message2') ?? ''"  
                            :canClose="true" />
                    @endif

                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-id-badge"></i> Employee Position</h3>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Role</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">Developer</div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Designations</label>
                        <div class="col-sm-4 designation-badge-container">
                            <div class="premium-badge badge-designation-primary"><i class="fa fa-code"></i> Software Engineer</div>
                            <div class="premium-badge badge-designation-info"><i class="fa fa-users"></i> Team Lead</div>
                            <div class="premium-badge badge-designation-success"><i class="fa fa-star"></i> Scrum Master</div>
                        </div>
                    </div>





                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-user-circle-o"></i> Personal Information</h3>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">First Name</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">John</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Last Name</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">Doe</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">john.doe@example.com</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Username</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">johndoe</div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-id-card-o"></i> Demographic Information</h3>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Date of Birth</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">01/01/1990</div>
                        </div>
                    </div>                                             
                
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Gender</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">Male</div>
                        </div>
                    </div>                                                      
                
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Address</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">123 Main St, Springfield</div>
                        </div>
                    </div>                                                    
                        
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Phone Number</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">+94 77 123 4567</div>
                        </div>
                    </div>                                                
                
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">NIC Number</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">123456789V</div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-briefcase"></i> Professional Information</h3>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Hourly Rate</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">$25.00</div>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Monthly Salary</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">$5000.00</div>
                        </div>
                    </div>                                                    
                                                                      
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">EPF/ETF Details</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">EPF: 12345, ETF: 67890</div>
                        </div>
                    </div>                                                        

                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-graduation-cap"></i> Qualifications & Skills</h3>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Education qualifications</label>
                        <div class="col-sm-8">
                            <div class="border border-edu p-2">
                                <h4>Bachelor of Science in Computer Science</h4>
                                <p class="text-muted">University of Technology, 2016 - 2020</p>
                                <p>Graduated with First Class Honours. Specialization in Software Engineering and Artificial Intelligence.</p>
                                <h5>Key Achievements:</h5>
                                <ul class="list-disc pl-5">
                                    <li>Dean's List for all 8 semesters</li>
                                    <li>Lead Developer for the University Capstone Project</li>
                                    <li>President of the Computer Science Society</li>
                                </ul>
                                <p><strong>Relevant Coursework:</strong> Data Structures, Algorithms, Database Systems, Web Development, Machine Learning.</p>
                            </div>
                        </div>
                    </div>                                                    

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Skills</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">PHP, Laravel, JavaScript</div>
                        </div>
                    </div>                                                    

                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-clock-o"></i> Employment Details</h3>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Account Status</label>
                        <div class="col-sm-8">
                            <span class="label label-primary py-2 px-3 text-base mr-2">Enabled</span>
                            <span class="label label-warning py-2 px-3 text-base">Disabled</span>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Employment Status</label>
                        <div class="col-sm-8">
                            <div class="form-control-static">
                                <span class="premium-badge status-pending mr-2"><i class="fa fa-clock-o"></i> Pending</span>
                                <span class="premium-badge status-active mr-2"><i class="fa fa-check-circle"></i> Active</span>
                                <span class="premium-badge status-resigned mr-2"><i class="fa fa-sign-out"></i> Resigned</span>
                                <span class="premium-badge status-terminated"><i class="fa fa-times-circle"></i> Terminated</span>    
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Termination Date</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">-</div>
                        </div>
                    </div>
               
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Date Joined</label>
                        <div class="col-sm-8">
                            <div class="form-control-static text-sm">01/01/2020</div>
                        </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>
                    <h3 class="mb-3 font-bold text-lg"><i class="fa fa-camera"></i> Profile Picture</h3>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Profile image</label>
                        <div class="col-sm-8">
                            <img src="https://placehold.co/500x500" alt="Profile Image" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>                                              
                        
                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 offset-sm-4">
                            <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm font-semibold mr-2" style="min-width: 150px">Back</a>
                        </div>
                    </div>

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
    <script src="{{asset('plugins/summernote-0.8.18/summernote-bs4.js')}}"></script>
@stop


@section('javascript')
<script>
    // Scripts removed for view-only page
</script>
@stop
