@extends('layouts.master',['title' => 'Dev Workload'])
@section('title','Dev Workload')



@section('css-files')    
@stop

@section('page-css')
    <style>
        /* Profile Header */
        .profile-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 10px 30px;
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



        

        /* Grid Layout */
        

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
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

        

        /* 2x2 Info Grid Styling */
        .info-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .info-badge {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            /* Flex basis for 2x2 grid (minus gap) */
            flex: 1 1 calc(50% - 15px);
            min-width: 250px;
            transition: all 0.2s ease;
        }
        .info-badge.three-cols {
            flex: 1 1 calc(33% - 15px);
        }
        .info-badge.four-cols {
            flex: 1 1 calc(25% - 15px);
            min-width: 235px;
        }

        .info-badge:hover {
            background: #ffffff;
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .info-badge i {
            font-size: 26px; /* Bigger Icons */
            margin-right: 18px;
            color: #667eea;
            width: 32px;
            text-align: center;
        }
        .info-badge .info-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #718096;
            display: block;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .info-badge .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
        }



       




        .badge-priority {
            padding: 0.25em 0.5em;
            border-radius: 4px;
            /*font-size: 0.85rem;
            font-weight: 700;*/
            text-transform: capitalize;
            min-width: 75px;
            display: inline-block;
            font-weight: bold;
        }
        .priority-critical { background: #fbc9c9; color: #991b1b; }
        .priority-high { background: #ffbf69e0; color: #9a3412; }
        .priority-medium { background: #fbf39e; color: #854d0e; }
        .priority-low { background: #a4f7c1; color: #166534; }



        /*== Task items ==*/
        .task-item-feature-title{  
            margin: 20px 0px 15px;
            padding-left: 10px;
            border-left: 4px solid #1ab394;
        }
        
        .task-item{
            margin: 0;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            transition: background 0.2s ease;
        }

        .task-item:hover {
            background: #fcfcfc;
        }

        .task-icon{
            float: left;
            text-align: center;
            width: 45px;
            height: 45px;
            margin-right: 15px;
            /*background: #f8fafc;*/
            border-radius: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .task-item-title{
            color: #1e293b;
            display: block;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .task-item-title:hover {
            color: #1ab394;
        }

        .task-item .task-sub-title {
            color: #64748b;
            margin-left: 60px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 1px;
        }

        .task-details-grid {
            margin-left: 60px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .task-meta-item {
            font-size: 12px;
            color: #94a3b8;
        }

        .task-meta-label {
            font-weight: 700;
            color: #475569;
            margin-right: 4px;
        }

        .task-info{
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 10px;
        }      

        .task-item .views-number{
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .task-item small {
            color: #94a3b8;
            font-size: 11px;
        }

        .task-icon .fa {
            font-size: 36px;
            color: #64748b;
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
                    
                    <div class="d-flex align-items-center my-3" style="gap: 15px;">
                        <div>
                            <span class="label label-primary py-1 px-3 text-xs">Enabled</span>
                        </div>                        
                        <div style="width: 1px; height: 18px; background: #e2e8f0;"></div>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="premium-badge status-active"><i class="fa fa-check-circle mr-1"></i> Active</span>
                        </div>                        
                    </div>
                    <hr/>
                    <div class="mt-2">
                        <div class="premium-badge badge-designation-primary mr-2"><i class="fa fa-code mr-1"></i> Software Engineer</div>
                        <div class="premium-badge badge-designation-info mr-2"><i class="fa fa-users mr-1"></i> Team Lead</div>
                        <div class="premium-badge badge-designation-success"><i class="fa fa-star mr-1"></i> Scrum Master</div>
                    </div>
                </div>
                <div class="ml-auto text-right">
                    <div class="info-label mb-1">Date Joined</div>
                    <div class="font-bold text-lg">January 01, 2020</div>
                </div>
            </div>

            <div class="ibox-content m-b-sm border-bottom">
                <h2 class="mb-4 font-bold text-muted">Select Date Range</h2>
                <div class="row">                 
                    <div class="col-lg-6">
                        <label for="project-select" class="font-weight-bold mb-2">Start Date:</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                    </div>                    
                    <div class="col-lg-6">
                        <label for="project-select" class="font-weight-bold mb-2">End Date:</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>                       
                </div>     
            </div>

            <div class="ibox-content m-b-sm border-bottom">        
                <h2 class="m-0 font-bold text-dark">Task Status Overview</h2>
                <div class="info-group">                    
                    <div class="info-badge three-cols">
                        <i class="fa fa-th-list" style="color: #3498db;"></i>
                        <div>
                            <span class="info-label">Total Assigned</span>
                            <span class="info-value">37</span>
                        </div>
                    </div>
                    <div class="info-badge three-cols">
                        <i class="fa fa-hourglass-2" style="color: #f39c12;"></i>
                        <div>
                            <span class="info-label">Pending</span>
                            <span class="info-value">11</span>
                        </div>
                    </div>
                    <div class="info-badge three-cols">
                        <i class="fa fa-check-square" style="color: #1ab394;"></i>
                        <div>
                            <span class="info-label">Completed</span>
                            <span class="info-value">26</span>
                        </div>
                    </div>                                
                </div>
            </div>

            <div class="ibox-content m-b-sm border-bottom">        
                <h2 class="m-0 font-bold text-dark">Completed Tasks Summary</h2>
                <div class="info-group">
                    <!-- Client Detail -->
                    <div class="info-badge four-cols">
                        <i class="fa fa-tasks"></i>
                        <div>
                            <span class="info-label">Total Completed Tasks</span>
                            <span class="info-value">37</span>
                        </div>
                    </div>
                    <!-- Project Detail -->
                    <div class="info-badge four-cols">
                        <i class="fa fa-clock-o"></i>
                        <div>
                            <span class="info-label">Total Estimate Time</span>
                            <span class="info-value">120h 50m</span>
                        </div>
                    </div>
                    <!-- Parent Task Detail -->
                    <div class="info-badge four-cols">
                        <i class="fa fa-hourglass-end"></i>
                        <div>
                            <span class="info-label">Total Spend Time</span>
                            <span class="info-value">100h 30m</span>
                        </div>
                    </div>
                    <!-- Parent Task Detail -->
                    <div class="info-badge four-cols">
                        <i class="fa fa-line-chart"></i>
                        <div>
                            <span class="info-label">Overall Efficiency</span>
                            <span class="info-value">65%</span>
                        </div>
                    </div>            
                </div>
                
                <h3 class="mt-3 mb-2 font-bold text-dark">Delivered Summary</h3>
                <div class="info-group">                    
                    <div class="info-badge three-cols">
                        <i class="fa fa-rocket" style="color: #1ab394;"></i>
                        <div>
                            <span class="info-label">Early Delivered</span>
                            <span class="info-value">37</span>
                        </div>
                    </div>
                    <div class="info-badge three-cols">
                        <i class="fa fa-calendar-check-o" style="color: #23c6c8;"></i>
                        <div>
                            <span class="info-label">On Time Delivered</span>
                            <span class="info-value">50</span>
                        </div>
                    </div>
                    <div class="info-badge three-cols">
                        <i class="fa fa-warning" style="color: #ed5565;"></i>
                        <div>
                            <span class="info-label">Delayed Delivered</span>
                            <span class="info-value">20</span>
                        </div>
                    </div>                                
                </div>
            </div>


            
            <div class="ibox">
                <div class="ibox-content">                        
                    
                    <div class="task-item-feature-title">
                        <h3 class="">Recently Completed Tasks</h3>
                    </div>                    

                    <div class="task-item">
                        <div class="row">                         
                            <div class="col-md-8">
                                <div class="task-icon">
                                    <i class="fa fa-check-circle text-navy"></i>
                                </div>
                                <a href="#" class="task-item-title">Develop ERP system UI</a>
                                <div class="task-sub-title">Refined the dashboard layouts and optimized the responsive behavior for mobile devices.</div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Project:</span>
                                        <span>Inventory Management System</span>
                                    </div> 
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Phase:</span>
                                        <span class="">Frontend</span>
                                    </div>                                    
                                </div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Assigned:</span>
                                        <span>2025 Feb 12</span>
                                    </div>
                                </div>                                
                            </div>                       
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">
                                <div class="mb-2">
                                    <span class="badge-priority priority-medium">
                                        <i class="fa fa-info-circle mr-1"></i> Medium
                                    </span>
                                </div>                                
                                <div class="views-number">
                                    <small>Estimate:</small> 12h 30m
                                </div>
                                <div class="views-number mt-1">
                                    <small>Spent:</small> 15h 20m
                                </div>                                
                            </div>                                
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">                                    
                                <span class="badge badge-primary uppercase mb-1 py-2">Completed</span>
                                <div>
                                    <span class="font-bold">2025/12/05</span><br>
                                    <small>(2.5 hours ago)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                                

                </div>
            </div>

            <div class="ibox">
                <div class="ibox-content">                        
                    <div class="task-item-feature-title">
                        <h3 class="">Recently Assigned Tasks</h3>
                    </div>                    

                    <div class="task-item">
                        <div class="row">                         
                            <div class="col-md-8">
                                <div class="task-icon">
                                    <i class="fa fa-plus-circle text-primary"></i>
                                </div>
                                <a href="#" class="task-item-title">Backend API Security Patch</a>
                                <div class="task-sub-title">Audit and implement security headers and CSRF protection across all public endpoints.</div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Project:</span>
                                        <span>Security Module Upgrade</span>
                                    </div> 
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Phase:</span>
                                        <span class="">Backend</span>
                                    </div>
                                </div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Assigned:</span>
                                        <span>2025 Feb 12</span>
                                    </div>
                                </div>
                            </div>                       
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">
                                <div class="mb-2">
                                    <span class="badge-priority priority-high">
                                        <i class="fa fa-fire mr-1"></i> High
                                    </span>
                                </div>                                
                                <div class="views-number text-navy">
                                    <small>Estimate:</small> 08h 00m
                                </div>
                            </div>                                
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">                                    
                                <span class="badge badge-info uppercase mb-1 py-2">In Progress</span>                                
                            </div>
                        </div>
                    </div>

                    <div class="task-item">
                        <div class="row">                         
                            <div class="col-md-8">
                                <div class="task-icon">
                                    <i class="fa fa-plus-circle text-primary"></i>
                                </div>
                                <a href="#" class="task-item-title">Unit Test Implementation</a>
                                <div class="task-sub-title">Write comprehensive unit tests for the authentication and registration controllers.</div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Project:</span>
                                        <span>Core Framework v2</span>
                                    </div> 
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Phase:</span>
                                        <span class="">Testing</span>
                                    </div>
                                </div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Assigned:</span>
                                        <span>2025 Feb 12</span>
                                    </div>
                                </div>
                            </div>                       
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">
                                <div class="mb-2">
                                    <span class="badge-priority priority-low">
                                        <i class="fa fa-level-down mr-1"></i> Low
                                    </span>
                                </div>                                
                                <div class="views-number text-navy">
                                    <small>Estimate:</small> 16h 00m
                                </div>
                            </div>                                
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">                                    
                                <span class="badge badge-info uppercase mb-1 py-2">In Progress</span>                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            


            <div class="ibox">
                <div class="ibox-content">                        
                    <div class="task-item-feature-title">
                        <h3 class="">Delayed Tasks</h3>
                    </div>                    

                    <div class="task-item">
                        <div class="row">                         
                            <div class="col-md-8">
                                <div class="task-icon">
                                    <i class="fa fa-exclamation-triangle text-danger"></i>
                                </div>
                                <a href="#" class="task-item-title">Client Feedback Loop Fix</a>
                                <div class="task-sub-title">Fixing the delay in email notifications sent to clients after task submission.</div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Project:</span>
                                        <span>Client portal integration</span>
                                    </div> 
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Phase:</span>
                                        <span class="">High Priority</span>
                                    </div>                                    
                                </div>
                                <div class="task-details-grid">
                                    <div class="task-meta-item">
                                        <span class="task-meta-label">Assigned:</span>
                                        <span>2025 Feb 12</span>
                                    </div>
                                </div>
                            </div>                       
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">
                                <div class="mb-2">
                                    <span class="badge-priority priority-critical">
                                        <i class="fa fa-shield mr-1"></i> Critical
                                    </span>
                                </div>
                                <div class="views-number">
                                    <small>Estimate:</small> 12h 30m
                                </div>                               
                                
                            </div>                                
                            <div class="col-md-2 task-info border-l border-dotted border-gray-300">                                    
                                <span class="badge badge-danger uppercase mb-1 py-2">Overdue</span>
                                <div class="views-number text-danger">
                                    <small>Deadline:</small> 2025/03/01<br>
                                    <small class="font-bold text-danger">(Action Required)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="ibox">
                <div class="ibox-content forum-container">
                    <h2 class="mb-4 font-bold text-muted">Assigned Projects</h2>
                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Project</th>
                            <th class="text-center">Start Date</th>
                            <th class="text-center">Delivery Date<br><small>(Planned)</small></th>
                            <th class="text-center">Priority</th>                    
                            <th class="text-center">% Done</th>                    
                            <th class="text-center">Project Overlap</th>     
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                            <tr id='addr0'>
                                <td>Project{{ $i }}</td>
                                <td class="text-navy text-base font-bold">2025/1/5</td>
                                <td class="text-red text-base font-bold">2025/11/5</td>
                                <td>
                                    @if($i==0)
                                        <span class="badge-priority priority-medium d-inline-flex align-items-center shadow-sm mr-2">
                                            <i class="fa fa-info-circle mr-2"></i> Medium
                                        </span>
                                    @elseif($i==1)
                                        <span class="badge-priority priority-critical d-inline-flex align-items-center shadow-sm mr-2">
                                            <i class="fa fa-shield mr-2"></i> Critical
                                        </span>
                                    @elseif($i==2)
                                        <span class="badge-priority priority-high d-inline-flex align-items-center shadow-sm mr-2">
                                            <i class="fa fa-fire mr-2"></i> High
                                        </span>
                                    @else
                                        <span class="badge-priority priority-low d-inline-flex align-items-center shadow-sm mr-2">
                                            <i class="fa fa-level-down mr-2"></i> low
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted font-bold text-lg">5%</td>
                                <td><i class="fa fa-caret-left text-info mr-2"></i>Partial Overlap (Left)</td>
                            </tr>
                            @endfor
                            <tr id='addr8'>
                                <td>Project ABC</td>
                                <td class="text-navy text-base font-bold">2025/1/5</td>
                                <td class="text-red text-base font-bold">2025/11/5</td>
                                <td>                                   
                                    <span class="badge-priority priority-critical d-inline-flex align-items-center shadow-sm mr-2">
                                        <i class="fa fa-shield mr-2"></i> Critical
                                    </span>
                                </td>
                                <td class="text-muted font-bold text-lg">44%</td>
                                <td><i class="fa fa-arrows-h text-success mr-2"></i>Full Overlap</td>                   
                            </tr>
                            <tr id='addr9'>
                                <td>Project BCD</td>
                                <td class="text-navy text-base font-bold">2025/1/5</td>
                                <td class="text-red text-base font-bold">2025/11/5</td>
                                <td>
                                    <span class="badge-priority priority-low d-inline-flex align-items-center shadow-sm mr-2">
                                        <i class="fa fa-level-down mr-2"></i> low
                                    </span>                                    
                                </td>
                                <td class="text-muted font-bold text-lg">56%</td>
                                <td><i class="fa fa-caret-right text-warning mr-2"></i>Partial Overlap (Right)</td>                        
                            </tr>
                        </tbody>                       
                    </table>

                        
                </div>
            </div>









        </div>
    </div>
@stop

@section('script-files')
@stop

@section('javascript')
@stop



