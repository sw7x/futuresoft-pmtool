@extends('layouts.master',['title' => 'Approve Timesheet'])
@section('title','Approve Timesheet')




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
                            @for ($i = 0; $i < 9; $i++)
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

                    <div class="text-right">
                        <button class="btn btn-primary btn-sm w-20">Approve</button>
                        <button class="btn btn-danger btn-sm w-20">Decline</button>
                    </div>
                    

                </div>
            </div>
        
        </div>
    </div>
@stop
    
              
@section('bootstrap-modals')
    <!-- Edit Time Modal -->
    <div class="modal fade" id="editTimeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Time Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Minutes</label>
                        <input type="number" id="newTimeValue" class="form-control" placeholder="Enter minutes">
                    </div>
                    <div class="form-group">
                        <label>Explanation</label>
                        <textarea id="editExplanation" class="form-control" rows="3"  maxlength="200" placeholder="Why was this edited?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-xs" id="saveTimeBtn">Save changes</button>
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
            var selectedCell = null;


            console.log('111');

            

            // Handle cell click
            $(document).on('click', '.editable-cell', function() {
                selectedCell = $(this);
                
                // Extract current numeric value (e.g., "30min" -> 30)
                // If it contains a strikethrough, get the second value
                var currentHtml = selectedCell.html();
                var currentValueStr = "";
                
                if (selectedCell.find('.line-through').length > 0) {
                    // It has been edited, get the text after the span
                    currentValueStr = selectedCell.contents().filter(function() {                        
                        return this.nodeType === 3; // Text nodes
                    }).text().trim();
                } else {
                    currentValueStr = selectedCell.text().trim();
                }
                
                var currentVal = parseInt(currentValueStr) || 0;
                $('#newTimeValue').val(currentVal);
                
                // Get existing explanation/tooltip
                $('#editExplanation').val(selectedCell.attr('data-original-title') || "");
                
                $('#editTimeModal').modal('show');
            });

            // Save changes
            $('#saveTimeBtn').click(function() {
                var newValue = $('#newTimeValue').val();
                var explanation = $('#editExplanation').val();
                
                if (newValue === "" || newValue === null) newValue = 0;
                var newValueWithMin = newValue + 'min';
                
                var devSubmitValText = "";// devloper submit time value  of the data cell
                var editedValText = "";   // manager edit devloper submit time value and put this value to the data cell 
                
                // Determine original vs current state
                if (selectedCell.find('.line-through').length > 0) {
                    devSubmitValText = selectedCell.find('.line-through').text().trim();
                    editedValText = selectedCell.contents().filter(function() {
                        return this.nodeType === 3;
                    }).text().trim();
                } else {
                    devSubmitValText = selectedCell.text().trim();
                    editedValText = devSubmitValText;
                }

                // 1. Update the HTML Visuals
                if (newValueWithMin === devSubmitValText) {
                    selectedCell.html(devSubmitValText);
                    selectedCell.removeClass('edit');
                } else {
                    selectedCell.html('<span class="mr-2 line-through text-red">' + devSubmitValText + '</span>' + newValueWithMin);
                    selectedCell.addClass('edit');
                }

                // 2. Handle the Tooltip (the Explanation)
                selectedCell.attr('data-original-title', explanation); // Set title so tooltip can read it
                selectedCell.tooltip('dispose'); // Remove any old tooltip instance

                if (explanation && explanation.trim() !== "") {
                    // Initialize the new tooltip immediately on this specific cell
                    selectedCell.tooltip({
                        placement: 'right'
                    });
                }
                
                $('#editTimeModal').modal('hide');
            });

            
            // Reset modal on close
            $('#editTimeModal').on('hidden.bs.modal', function () {
                $('#newTimeValue').val('');
                $('#editExplanation').val('');
            });




            // Handle the legacy tooltips (initial load)
            $('#wTimesheetTable td.edit').tooltip({
                placement: 'right'
            });




         
        });
</script>
@stop


