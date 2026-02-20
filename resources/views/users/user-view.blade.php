@extends('layouts.master',['title' => 'User Profile'])
@section('title','User Profile')

@section('css-files')    
@stop

@section('page-css')
    <style>
        /* Profile Header */
        .profile-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 30px;
            border-radius: 0px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 30px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 0px;
            object-fit: cover;
            border: 4px solid #f8fafc;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .profile-main-info h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .profile-main-info .role-text {
            display: inline-block;
            margin-top: 5px;
            color: #64748b;
            font-size: 16px;
            font-weight: 500;
        }



        /* Info Cards */
        .info-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0px;
            margin-bottom: 25px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .info-card-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #334155;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-header h5 i {
            color: #1ab394;
            font-size: 16px;
        }

        .info-card-body {
            padding: 25px;
        }

        /* Grid Layout */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px 40px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-value {
            font-size: 15px;
            color: #334155;
            font-weight: 500;
        }



        /* Qualification Styling */
        .qualification-box {
            background: #f8fafc;
            border-left: 4px solid #1ab394;
            padding: 20px;
            border-radius: 0;
        }

        .qualification-box h4 {
            font-weight: 700;
            margin-bottom: 5px;
            color: #1e293b;
        }

        /* Existing Badge Adaptations */
        .premium-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 11px;
            border-radius: 0px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-designation-primary { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .badge-designation-info { background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; }
        .badge-designation-success { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        
        .status-active { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-pending { background: #f9fafb; color: #374151; border: 1px solid #e5e7eb; }
        .status-resigned { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .status-terminated { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .footer-action {
            margin-top: 20px;
            padding-bottom: 50px;
            display: flex;
            justify-content: flex-end;
            gap: 5px;
        }
    </style>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-12">
            
            @if(Session::has('pm_add_message'))
                <x-flash-message  
                    :class="Session::get('pm_add_cls', 'flash-info')"  
                    :title="Session::get('pm_add_msgTitle') ?? 'Info!'" 
                    :message="Session::get('pm_add_message') ?? 'Info!'"  
                    :message2="Session::get('pm_add_message2') ?? ''"  
                    :canClose="true" />
            @endif

            <!-- Profile Header Section -->
            <div class="profile-header">
                <div class="profile-avatar-wrapper">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" alt="User Avatar" class="profile-avatar">
                </div>
                <div class="profile-main-info">
                    <h2>John Doe</h2>
                    <span class="role-text">Developer</span>
                    
                    <div class="d-flex align-items-center mt-3" style="gap: 15px;">
                        <div>
                            <span class="label label-primary py-1 px-3 text-xs">Enabled</span>
                        </div>
                        <div>
                            <span class="label label-warning py-1 px-3 text-xs">Disabled</span>
                        </div>
                        <div style="width: 1px; height: 18px; background: #e2e8f0;"></div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="premium-badge status-pending"><i class="fa fa-check-circle mr-1"></i> Pending</span>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="premium-badge status-active"><i class="fa fa-check-circle mr-1"></i> Active</span>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="premium-badge status-resigned"><i class="fa fa-check-circle mr-1"></i> Resigned</span>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="premium-badge status-terminated"><i class="fa fa-check-circle mr-1"></i> Terminated</span>
                        </div>
                    </div>
                </div>
                <div class="ml-auto text-right">
                    <div class="info-label mb-1">Date Joined</div>
                    <div class="font-bold text-lg">January 01, 2020</div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-7">
                    <!-- Personal Info -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5><i class="fa fa-user"></i> Personal Details</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-grid" style="grid-template-columns: 1fr;">
                                <div class="info-item">
                                    <span class="info-label">Full Name</span>
                                    <span class="info-value">John Doe</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Gender</span>
                                    <span class="info-value">Male</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Username</span>
                                    <span class="info-value">johndoe</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email Address</span>
                                    <span class="info-value text-primary font-bold">john.doe@example.com</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Phone Number</span>
                                    <span class="info-value">+94 77 123 4567</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">NIC Number</span>
                                    <span class="info-value">123456789V</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Date of Birth</span>
                                    <span class="info-value">January 01, 1990 (34 Years)</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Residential Address</span>
                                    <span class="info-value">123 Main St, Springfield, United States</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Qualifications -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5><i class="fa fa-graduation-cap"></i> Education & Skills</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="qualification-box mb-4">
                                <h4>Bachelor of Science in Computer Science</h4>
                                <div class="text-sm font-semibold text-primary mb-2">University of Technology | 2016 - 2020</div>
                                <p class="text-muted mb-0">Graduated with First Class Honours. Specialization in Software Engineering and Artificial Intelligence. Lead Developer for the University Capstone Project.</p>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Core Competencies</span>
                                <div class="mt-2">
                                    <span class="badge badge-primary px-3 py-2 mr-1">PHP</span>
                                    <span class="badge badge-primary px-3 py-2 mr-1">Laravel</span>
                                    <span class="badge badge-primary px-3 py-2 mr-1">JavaScript</span>
                                    <span class="badge badge-primary px-3 py-2">MySQL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-5">
                    <!-- Workspace Info -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5><i class="fa fa-id-badge"></i> Designation & Role</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-item mb-4">
                                <span class="info-label">Primary Role</span>
                                <span class="info-value">Developer</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Assigned Designations</span>
                                <div class="mt-2 d-flex flex-column gap-2">
                                    <div class="premium-badge badge-designation-primary mb-2"><i class="fa fa-code mr-1"></i> Software Engineer</div>
                                    <div class="premium-badge badge-designation-info mb-2"><i class="fa fa-users mr-1"></i> Team Lead</div>
                                    <div class="premium-badge badge-designation-success"><i class="fa fa-star mr-1"></i> Scrum Master</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial/Professional -->
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5><i class="fa fa-briefcase"></i> Professional Details</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-grid" style="grid-template-columns: 1fr;">
                                <div class="info-item">
                                    <span class="info-label">Monthly Salary</span>
                                    <span class="info-value font-bold text-lg">$5,000.00</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Hourly Rate</span>
                                    <span class="info-value text-muted">$25.00 / hr</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">EPF/ETF Registration</span>
                                    <span class="info-value">EPF: 12345 | ETF: 67890</span>
                                </div>
                                @if(isset($user->termination_date) || true) {{-- Added true for demo purposes --}}
                                <div class="info-item mt-3 pt-3 border-top">
                                    <span class="info-label">Termination Date</span>
                                    <span class="info-value text-danger font-bold">October 15, 2023</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="footer-action">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm font-semibold mr-2">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
                <a href="#" class="btn btn-primary btn-sm font-semibold">
                    <i class="fa fa-pencil mr-2"></i> Edit
                </a>
            </div>

        </div>
    </div>
@stop

@section('script-files')
@stop

@section('javascript')
@stop
