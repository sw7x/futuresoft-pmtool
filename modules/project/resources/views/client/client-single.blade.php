@extends('core-module::layouts.master',['title' => 'Client single'])
@section('title','Client single')



@section('css-files')
    <style>
        .client-profile-header {
            background: linear-gradient(135deg, #1ab394 0%, #0d8a72 100%);
            padding: 30px;
            color: white;
            border-radius: 0;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .client-avatar-container {
            width: 120px;
            height: 120px;
            border-radius: 0%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .client-avatar-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .client-name-title h2 {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
            letter-spacing: -0.5px;
        }

        .client-name-title p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .info-card-header {
            padding: 15px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #1a202c;
            font-size: 16px;
        }

        .info-card-header i {
            color: #1ab394;
        }

        .info-card-body {
            padding: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 40px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 500;
        }

        .info-value-empty {
            color: #94a3b8;
            font-style: italic;
            font-size: 14px;
        }

        .badge-type {
            padding: 4px 12px;
            border-radius: 0px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-initial { background: #e0f2fe; color: #0369a1; }
        .badge-company { background: #f0fdf4; color: #15803d; }

        .full-width {
            grid-column: span 2;
        }

        .action-footer {
            margin-top: 20px;
            text-align: right;
            padding: 0 0 40px;
        }



        
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            
            <!-- Profile Header -->
            <div class="client-profile-header">
                <div class="client-avatar-container">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Client Avatar">
                </div>
                <div class="client-name-title">
                    <h2>{{ $client->name ?? 'Christopher J. Miller' }}</h2>
                    <p><i class="fa fa-building-o"></i> {{ $client->company_name ?? 'Apex Solutions Ltd.' }}</p>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fa fa-user"></i>
                    <h5>General Information</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-user-o mr-1"></i> Client Name</span>
                            <span class="info-value">{{ $client->name ?? 'Christopher J. Miller' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-building-o mr-1"></i> Company Name</span>
                            <span class="info-value">{{ $client->company_name ?? 'Apex Solutions Ltd.' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Client Type</span>
                            <span class="info-value">
                                @php $type = $client->client_type ?? 'company'; @endphp
                                <span class="badge-type {{ $type == 'initial' ? 'badge-initial' : 'badge-company' }}">
                                    {{ ucfirst($type) }}
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-calendar-plus-o mr-1"></i> Account Creation Date</span>
                            <span class="info-value">{{ $client->created_at ?? 'October 24, 2023' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Location -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fa fa-envelope"></i>
                    <h5>Contact & Location Details</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-envelope-o mr-1"></i> Email Address(es)</span>
                            <div class="info-value text-primary">
                                <div>{{ $client->email ?? 'chris.miller@apexsolutions.com' }}</div>
                                <div>{{ $client->secondary_email ?? 'billing@apexsolutions.com' }}</div>
                                <div>{{ $client->secondary_email ?? 'company.admin@apexsolutions.com' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-phone mr-1"></i> Phone Number(s)</span>
                            <div class="info-value">
                                <div>{{ $client->phone ?? '+1 (555) 098-7654' }}</div>
                                <div style="">{{ $client->office_phone ?? '+1 (555) 123-4567' }}</div>
                                <div style="">{{ $client->office_phone ?? '+1 (664) 753-4667' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label"><i class="fa fa-globe mr-1"></i> Country</span>
                            <span class="info-value">{{ $client->country ?? 'United States' }}</span>
                        </div>
                        <div class="info-item full-width">
                            <span class="info-label"><i class="fa fa-map-marker mr-1"></i> Physical Address</span>
                            <span class="info-value">
                                {!! nl2br(e($client->address ?? "4528 Commercial Way,\nSpring Hill,\nFL 34606")) !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fa fa-info-circle"></i>
                    <h5>Additional Notes</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-item full-width">
                            <span class="info-label">Description</span>
                            <div class="info-value">
                                {{ $client->description ?? 'Primary contact for enterprise-level cloud migration projects. Apex Solutions is a long-term partner specializing in FinTech infrastructure.' }}
                            </div>
                        </div>
                        <div class="info-item full-width">
                            <span class="info-label">Internal Comments</span>
                            <div class="info-value">
                                @if(isset($client->comments) && $client->comments)
                                    {{ $client->comments }}
                                @else
                                    <span class="info-value-empty">No internal comments recorded.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ibox-content m-b-sm border-bottom">
        
                <h3 class="mb-3 font-bold text-dark">Client Summary</h3>
                

                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-3">
                            <div class="card-body p-3 border-gray-200 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 text-primary"><i class="fa fa-briefcase fa-2x"></i></div>
                                    <div>
                                        <div class="font-bold text-sm">Total Projects</div>
                                        <div class="text-base text-muted">23</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-3">
                            <div class="card-body p-3 border-gray-200 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 text-info"><i class="fa fa-check-circle-o fa-2x"></i></div>
                                    <div>
                                        <div class="font-bold text-sm">Finished Projects</div>
                                        <div class="text-base text-muted">11</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-3">
                            <div class="card-body p-3 border-gray-200 border rounded">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 text-success"><i class="fa fa-clock-o fa-2x"></i></div>
                                    <div>
                                        <div class="font-bold text-sm">Pending Projects</div>
                                        <div class="text-base text-muted">12</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- <div class="info-group">
                    <!-- Project Detail -->
                    <div class="info-badge">
                        <i class="fa fa-briefcase"></i>
                        <div>
                            <span class="info-label">Project</span>
                            <span class="info-value">FutureSoft ERP Update</span>
                        </div>
                    </div>

                    <!-- Client Detail -->
                    <div class="info-badge">
                        <i class="fa fa-building-o"></i>
                        <div>
                            <span class="info-label">Client</span>
                            <span class="info-value">Global Solutions Inc.</span>
                        </div>
                    </div>

                    <!-- Parent Task Detail -->
                    <div class="info-badge">
                        <i class="fa fa-level-up"></i>
                        <div>
                            <span class="info-label">Parent Task</span>
                            <span class="info-value">Database Migration Module</span>
                        </div>
                    </div>

                    <!-- Project Status -->
                    <div class="info-badge">
                        <i class="fa fa-bullseye" style="color: #38a169;"></i>
                        <div>
                            <span class="info-label">Project Status</span>
                            <span class="info-value">In Progress (65%)</span>
                        </div>
                    </div>
                </div> --}}
            


            </div>



            <!-- Buttons -->
            <div class="action-footer">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm font-semibold mr-2">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <a href="#" class="btn btn-info btn-sm font-semibold">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>

            



        </div>
    </div>
@stop

@section('script-files')
@stop

@section('javascript')
@stop
