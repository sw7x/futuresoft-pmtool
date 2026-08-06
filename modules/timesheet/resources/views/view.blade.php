@extends('core-module::layouts.master',['title' => 'Empty'])
@section('title','View Timesheet')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        .timesheetTable thead th {
            background-color: #1ab394;
            vertical-align: middle !important;
        }
        .row-total-box {
            font-weight: 700;
            color: #1ab394;
        }
        .grand-total-cell {
            font-size: 1.1rem;
            color: #1ab394;
        }
        .timesheetTable th, .timesheetTable td {
            vertical-align: middle !important;
            position: relative;
        }
        .editable-cell {
            cursor: pointer;
            transition: background 0.2s;
        }
        .editable-cell:hover {
            background-color: #f1f5f9 !important;
        }
        .text-red {
            color: #ed5565;
        }
        .line-through {
            text-decoration: line-through;
        }
        /* Comment icon for cells with explanations */
        .editable-cell[data-original-title]:not([data-original-title=""])::after {
            content: "\f075"; /* fa-comment */
            font-family: FontAwesome;
            position: absolute;
            top: -2px;
            right: 0px;
            font-size: 12px;
            color: #6C757D;
        }

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
    </style>
@stop


@section('content')
    <div class="ibox-content m-b-sm border-bottom">        
        <h2 class="m-0 font-bold text-dark">Timesheet Context</h2>
        
        <div class="info-group">
            <!-- Project Detail -->
            <div class="info-badge">
                <i class="fa fa-vcard-o"></i>
                <div>
                    <span class="info-label">Designation</span>
                    <span class="info-value">Senior Software Engineer</span>
                </div>
            </div>

            <!-- Client Detail -->
            <div class="info-badge">
                <i class="fa fa-user"></i>
                <div>
                    <span class="info-label">Employee</span>
                    <span class="info-value">John Doe</span>
                </div>
            </div>

            <!-- Parent Task Detail -->
            <div class="info-badge">
                <i class="fa fa-calendar"></i>
                <div>
                    <span class="info-label">Week</span>
                    <span class="info-value">2025/12/12 - 2025/12/19</span>
                </div>
            </div>

            <!-- Project Status -->
            <div class="info-badge">
                <i class="fa fa-calendar-check-o" style="color: #38a169;"></i>
                <div>
                    <span class="info-label">Submit Date</span>
                    <span class="info-value">2025/12/22</span>
                </div>
            </div>
        </div>
    </div>


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
                
                    <div class="panel panel-primary">
                        <div class="panel-heading text-base">Approved - 2018/01/01</div>
                    </div>            
                       
                    <div class="panel panel-warning">
                        <div class="panel-heading text-base">Pending Approval</div>
                    </div>
                                                   
                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Project</th>
                            <th class="text-center">Task</th>
                            <th class="text-center">Monday</th>
                            <th class="text-center">Tuesday</th>
                            <th class="text-center">Wednesday</th>
                            <th class="text-center">Thursday</th>
                            <th class="text-center">Friday</th>
                            <th class="text-center">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 8; $i++)
                            <tr id='addr{{ $i }}'>
                                <td>PRJ1{{ $i }}</td>
                                <td>Task{{ $i }}</td>
                                <td class="text-center editable-cell">{{ $i }}0min</td>
                                <td class="text-center editable-cell">{{ $i }}0min</td>
                                <td class="text-center editable-cell">{{ $i }}0min</td>
                                <td class="text-center editable-cell">0min</td>
                                <td class="text-center editable-cell">
                                    {{ $i >= 0 && $i <= 4 ? '0min' : $i . '0min' }}
                                </td>
                                <td class="text-center row-total-box">{{ $i * 50 }}min</td>
                            </tr>
                            @endfor
                            <tr id='addr8'>
                                <td>PRJ19</td>
                                <td>Task9</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center editable-cell">30min</td>
                                <td class="text-center editable-cell edit" data-original-title="Voluptate autem perferendis saepe. Voluptas">0min</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center row-total-box">390min</td>
                            </tr>
                            <tr id='addr9'>
                                <td>PRJ19</td>
                                <td>Task9</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center editable-cell edit" data-original-title="Tooltip on bottom9">
                                    <span class="mr-2 line-through text-red">90min</span>
                                    30min
                                </td>
                                <td class="text-center editable-cell">0min</td>
                                <td class="text-center editable-cell">90min</td>
                                <td class="text-center row-total-box">390min</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f8fafc; font-weight: bold;">
                                <th colspan="2" class="text-right">Daily Totals:</th>
                                <th class="text-center">8h:00m</th>
                                <th class="text-center">8h:00m</th>
                                <th class="text-center">8h:10m</th>
                                <th class="text-center">0h:00m<br><div class="text-sm text-red">(Day Off)</div></th>
                                <th class="text-center">8h:20m<br>
                                    <div class="text-sm text-red">Off</div>
                                    <div class="text-xs text-red">(8.30AM-12.30PM)</div>
                                </th>
                                <th class="text-center grand-total-cell">40h:20m</th>
                            </tr>
                        </tfoot>
                    </table>                    

                </div>
            </div>
        
        </div>
    </div>
@stop
    
              





@section('script-files')
    
@stop


@section('javascript')
<script>
        $(document).ready(function() {
            // Handle the legacy tooltips (initial load)
            $('#wTimesheetTable td.edit').tooltip({
                placement: 'right'
            });




         
        });
</script>
@stop


