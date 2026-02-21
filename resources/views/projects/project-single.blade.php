@extends('layouts.master',['title' => 'Project Profile'])
@section('title','Project Profile')

@php
/* Sample values for demonstration if $project is not provided */
if (!isset($project)) {
    $project = (object) [
        'project_name' => 'AI Enhanced Logistics Hub',
        'description' => 'A next-generation logistics management system integrating real-time AI route optimization and automated warehouse control systems.',
        'start_date' => '2024-01-15',
        'planned_delivery_date' => '2024-08-30',
        'actual_delivery_date' => '2025-02-13',
        'deadline' => '2024-09-15',
        'currency' => 'USD',
        'estimated_cost' => 150000.00,
        'actual_cost' => 62500.00,
        'billing_type' => 'fixed_cost',
        'payment_status' => 'paid',
        'locality' => 'foreign',
        'project_type' => 'client',
        'project_category' => 'software',
        'priority' => 'critical',
        'project_status' => 'enable',
        'progress' => 'in_progress',
        'manager' => (object) ['name' => 'Michael Stevens'],
        'documentation' => '
<h3>Executive Summary</h3>
<p>The <strong>AI Enhanced Logistics Hub</strong> project is a strategic initiative aimed at revolutionizing our regional distribution network through the integration of advanced artificial intelligence and automated systems. This project serves as the flagship for our <a href="https://example.com/smart-warehouse-2025" target="_blank" class="text-blue-600 hover:underline">"Smart Warehouse 2025"</a> vision, focusing on reducing operational latency and increasing throughput by an estimated 35%.</p>

<h3>1. Project Objectives</h3>
<ul>
    <li><strong>Autonomous Optimization:</strong> Implement real-time AI algorithms to manage fleet routing and warehouse storage allocation.</li>
    <li><strong>Infrastructure Modernization:</strong> Upgrade existing hardware to support IoT-enabled sensors and automated robotic pickers.</li>
    <li><strong>System Integration:</strong> Seamlessly connect the hub with existing ERP and supply chain management platforms.</li>
    <li><strong>Sustainability:</strong> Reduce the carbon footprint of logistics operations through energy-efficient routing and smart lighting systems.</li>
</ul>

<h3>2. Technical Architecture</h3>
<p>The system is built on a distributed microservices architecture, utilizing high-performance computing nodes for AI processing. Data from over 5,000 IoT sensors is ingested into a centralized data lake, where machine learning models predict demand spikes and identify potential bottlenecks before they occur. Detailed architecture diagrams can be found in the <a href="https://example.com/arch-docs" target="_blank" class="text-blue-600 hover:underline">Internal Architecture Wiki</a>.</p>
<p>Key tech stack components include:</p>
<ol>
    <li>Neural networks for predictive maintenance of warehouse machinery.</li>
    <li>Edge computing modules for local sensor data processing.</li>
    <li>Blockchain-based ledger for transparent and secure package tracking.</li>
</ol>

<h3>3. Current Implementation Phase</h3>
<p>We are currently in the <strong>Execution Phase (Q2)</strong>. The initial audit of the warehouse infrastructure was completed in Q1, identifying critical zones for sensor deployment. Current activities focus on the deployment of the central AI engine and the training of baseline models using historical logistics data.</p>
<p>Recent milestones achieved:</p>
<ul>
    <li>Successful pilot of the autonomous routing system in the Northern corridor.</li>
    <li>Installation of smart tracking beacons in the main sorting facility.</li>
    <li>Completion of the user acceptance testing (UAT) for the manager dashboard.</li>
</ul>

<h3>4. Risk Management & Mitigation</h3>
<p>The primary risks identified involve potential integration delays with legacy hardware and data security concerns related to IoT expansion. Mitigation strategies include a phased rollout approach and the implementation of end-to-end encryption for all data transmissions. We have also established a dedicated "War Room" for rapid response during the cutover period scheduled for late Q3.</p>

<h3>5. Strategic Impact</h3>
<p>Upon completion, the Logistics Hub will not only be a center for distribution but also a data engine that informs our global supply chain strategy. The insights generated from this hub will allow for pre-emptive stock positioning and significantly more accurate delivery windows for our end clients, strengthening our competitive position in the global market.</p>

<h3>6. External Resources & References</h3>
<p>For further technical details and project tracking, please refer to the following resources:</p>
<div class="mt-3">
    <ul class="list-none p-0 m-0">
        <li class="mb-2"><i class="fa fa-external-link mr-2 text-blue-500"></i> <a href="https://github.com/futuresoft/ai-logistics-core" target="_blank" class="text-blue-600 hover:underline font-medium">Core AI Engine Repository</a> - Private access required.</li>
        <li class="mb-2"><i class="fa fa-file-pdf-o mr-2 text-red-500"></i> <a href="https://shared.futuresoft.com/docs/logistics-blueprint-2025.pdf" target="_blank" class="text-blue-600 hover:underline font-medium">Technical Blueprint V2.1</a> - PDF Documentation.</li>
        <li class="mb-2"><i class="fa fa-trello mr-2 text-blue-400"></i> <a href="https://trello.com/b/xyz/ai-logistics-hub" target="_blank" class="text-blue-600 hover:underline font-medium">Sprint Management Board</a> - Real-time task tracking.</li>
    </ul>
</div>'
    ];
}
@endphp

@section('css-files')
    <style>
        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
            font-weight: 700;
        }
        .section-title:first-of-type {
            margin-top: 0;
        }
        .display-group {
            margin-bottom: 1.25rem;
        }
        .display-label {
            font-size: 0.85rem;
            font-weight: 600;
            {{-- color: #94a3b8; --}}
            margin-bottom: 0.25rem;
        }
        .display-value {
            font-size: 1rem;
            {{-- color: #1e293b; --}}
            font-weight: 500;
        }
        .display-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            min-height: 4rem;
        }
        .badge-priority {
            padding: 0.35em 0.8em;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .priority-critical { background: #fbc9c9; color: #991b1b; }
        .priority-high { background: #ffbf69e0; color: #9a3412; }
        .priority-medium { background: #fbf39e; color: #854d0e; }
        .priority-low { background: #a4f7c1; color: #166534; }
        
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-enabled { background: #dcfce7; color: #15803d; }
        .status-disabled { background: #f1f5f9; color: #64748b; }


        .footer-action{
            margin-top: 20px;
            padding-bottom: 50px;
            display: flex;
            justify-content: flex-end;
            gap: 5px;
        }



    </style>
@stop

@section('content')


    <div class="row" id="">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">

                    <!-- Project Identity -->
                    <div class="section-title"><i class="fa fa-rocket mr-2"></i> Project Identity</div>                    
                    
                    <div class="display-group">
                        <div class="display-label">Project Name</div>
                        <div class="display-value text-2xl font-bold">{{ $project->project_name ?? 'N/A' }}</div>
                    </div>
                    <div class="display-group">
                        <div class="display-label">Managed by (PM)</div>
                        <div class="display-value"><i class="fa fa-user-circle mr-1 text-slate-400"></i> {{ $project->manager->name ?? 'Unassigned' }}</div>
                    </div>
                    
                    <div class="display-group mt-3">
                        <div class="display-label">Description</div>
                        <div class="display-value text-slate-600 leading-relaxed italic border-l-4 border-slate-200 pl-4">
                            {{ $project->description ?? 'No description provided.' }}
                        </div>
                    </div>

                    <!-- Timeline & Status -->
                    <div class="section-title"><i class="fa fa-calendar mr-2"></i> Timeline & Status</div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Start Date</div>
                            <div class="display-value font-mono font-bold">{{ $project->start_date ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Planned Delivery</div>
                            <div class="display-value font-mono font-bold text-yellow-400">{{ $project->planned_delivery_date ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Actual Delivery</div>
                            <div class="display-value font-mono font-bold">
                                {{ $project->actual_delivery_date ?? 'In Progress' }}
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Deadline</div>
                            <div class="display-value font-mono text-red-500 font-bold">{{ $project->deadline ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Progress</div>
                            <div class="display-value">
                                @php
                                $progressClass = [
                                    'not_started' => 'badge-secondary',
                                    'in_progress' => 'badge-info',
                                    'completed' => 'badge-primary',
                                    'blocked' => 'badge-warning',
                                    'cancelled' => 'badge-danger'
                                ][$project->progress ?? 'not_started'] ?? 'badge-info';
                                @endphp                                
                                {{-- 
                                <span class="badge {{ $progressClass }} px-3 py-2 uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $project->progress ?? 'Not Started') }}
                                </span>
                                --}}
                                <span class="badge badge-secondary px-3 py-2 uppercase tracking-wider">Not Started</span><br><br>
                                <span class="badge badge-info px-3 py-2 uppercase tracking-wider">In Progress</span><br><br>
                                <span class="badge badge-primary px-3 py-2 uppercase tracking-wider">Completed</span><br><br>
                                <span class="badge badge-warning px-3 py-2 uppercase tracking-wider">Blocked</span><br><br>
                                <span class="badge badge-danger px-3 py-2 uppercase tracking-wider">Cancelled</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Priority</div>
                            <div class="display-value d-flex flex-wrap">
                                <span class="badge-priority priority-medium d-inline-flex align-items-center shadow-sm mr-2">
                                    <i class="fa fa-info-circle mr-2"></i> Medium
                                </span>
                                
                                <span class="badge-priority priority-critical d-inline-flex align-items-center shadow-sm mr-2">
                                    <i class="fa fa-shield mr-2"></i> Critical
                                </span>

                                <span class="badge-priority priority-high d-inline-flex align-items-center shadow-sm mr-2">
                                    <i class="fa fa-fire mr-2"></i> High
                                </span>

                                <span class="badge-priority priority-low d-inline-flex align-items-center shadow-sm mr-2">
                                    <i class="fa fa-level-down mr-2"></i> low
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Project Status</div>
                            <div class="display-value">                                
                                <span class="status-pill status-enabled"><i class="fa fa-check-circle mr-2"></i> Enabled</span>                                
                                <span class="status-pill status-disabled"><i class="fa fa-times-circle mr-2"></i> Disabled</span>                                
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="section-title"><i class="fa fa-money mr-2"></i> Finance & Billing</div>
                    <div class="row">
                        
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Estimated Cost</div>
                            <div class="display-value text-lg font-bold">
                                {{ $project->currency ?? 'USD' }} {{ number_format($project->estimated_cost ?? 0, 2) }}
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Actual Cost</div>
                            <div class="display-value text-lg font-bold text-slate-700">
                                {{ $project->currency ?? 'USD' }} {{ number_format($project->actual_cost ?? 0, 2) }}
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Billing Type</div>
                            <div class="display-value capitalize font-bold text-xs"><i class="fa fa-briefcase mr-2 text-slate-400"></i> Fixed Cost</div><br>
                            <div class="display-value capitalize font-bold text-xs"><i class="fa fa-hourglass-half mr-2 text-slate-400"></i> Time & Material</div>
                        </div>

                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Payment Status</div>
                            <div class="display-value">
                                <span class="text-blue-600 font-bold border-b-2 border-blue-100 mr-4"><i class="fa fa-file-text-o mr-1"></i> Not Invoiced</span>
                                <span class="text-blue-600 font-bold border-b-2 border-blue-100 mr-4"><i class="fa fa-adjust mr-1"></i> Partially Paid</span>
                                <span class="text-blue-600 font-bold border-b-2 border-blue-100"><i class="fa fa-check-circle mr-1"></i> Paid</span>
                            </div>
                        </div>

                    </div>

                    <!-- Classification -->
                    <div class="section-title"><i class="fa fa-tags mr-2"></i> Classification</div>
                    <div class="row">
                        <div class="col-md-4 display-group">
                            <div class="display-label">Locality</div>
                            <div class="display-value capitalize"><i class="fa fa-map-marker mr-2 text-slate-400"></i> Local</div>
                            <div class="display-value capitalize"><i class="fa fa-globe mr-2 text-slate-400"></i> Foreign</div>
                        </div>
                        <div class="col-md-4 display-group">
                            <div class="display-label">Project Type</div>
                            <div class="display-value capitalize"><i class="fa fa-home mr-2 text-slate-400"></i> Internal</div> 
                            <div class="display-value capitalize"><i class="fa fa-university mr-2 text-slate-400"></i> Client</div>
                            <div class="display-value capitalize"><i class="fa fa-flask mr-2 text-slate-400"></i> R&D</div>
                            <div class="display-value capitalize"><i class="fa fa-wrench mr-2 text-slate-400"></i> Maintenance</div>
                        </div>
                        <div class="col-md-4 display-group">
                            <div class="display-label">Category</div>
                            <div class="display-value capitalize"><i class="fa fa-code mr-2 text-slate-400"></i> Software</div>
                            <div class="display-value capitalize"><i class="fa fa-cubes mr-2 text-slate-400"></i> Infrastructure</div>
                            <div class="display-value capitalize"><i class="fa fa-bullhorn mr-2 text-slate-400"></i> Marketing</div>
                            <div class="display-value capitalize"><i class="fa fa-users mr-2 text-slate-400"></i> HR</div>
                            <div class="display-value capitalize"><i class="fa fa-ellipsis-h mr-2 text-slate-400"></i> Other</div>
                        </div>
                    </div>

                    <!-- Documentation -->
                    <div class="section-title"><i class="fa fa-file-text-o mr-2"></i> Documentation</div>
                    <div class="display-group">
                        <div class="display-box prose prose-slate max-w-none">
                            {!! $project->documentation ?? '<span class="text-slate-400 italic">No documentation available.</span>' !!}
                        </div>
                    </div>
                </div>
            </div> 

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
    <!-- No form plugins needed for display view -->
@stop

@section('javascript')
<script>
    $(document).ready(function(){
        console.log("Project profile loaded successfully.");
    });
</script>
@stop


