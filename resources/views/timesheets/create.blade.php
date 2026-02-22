@extends('layouts.master',['title' => 'Empty'])
@section('title','Submit Timesheet')




@section('css-files')    
    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">
@stop







@section('page-css')
    <style>
        .timesheetTable thead th {
            background-color: #1ab394;
            /*
            background-color: #f8fafc;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0 !important;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle !important;
            padding: 10px 8px !important;
            */
        }
        .timesheetTable tbody.entry-group {
            border-top: 3px solid #1ab394 !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .meta-row {
            background-color: #fdfdfd;
        }
        .data-row td {
            padding: 12px 8px !important;
            vertical-align: middle !important;
        }
        .form-control:focus {
            border-color: #1ab394 !important;
            box-shadow: none;
        }
        .label-stacked {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .row-total-box {
            background-color: #f8fafc;
            font-weight: 700;
            color: #1ab394;
        }
        .time-input-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 100px;
        }
        .time-input-group .input-group {
            flex: 1;
        }
        .time-input-group .input-group-text {
            padding: 0 6px !important;
            font-size: 0.65rem;
            font-weight: 700;
            background-color: #f1f5f9;
            color: #64748b;
            border-color: #e2e8f0;
        }
        .time-input-group .form-control {
            padding: 2px 4px !important;
            height: 28px !important;
            font-size: 0.8rem;
            border-color: #e2e8f0;
        }
        .hh-input, .mm-input {
            /*width: 100% !important;*/
        }

        .task-input{
            height: 28px !important;
            padding: 2px 4px !important;
            font-size: 0.8rem;
            border-color: #e2e8f0;
        }

        .delete-row-btn{
            padding: 2px 10px;
        }


    </style>
@stop


@section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        <h2 class="font-bold no-margins"><i class="fa fa-clock-o text-primary"></i> Timesheet Entry</h2>
        <small class="text-muted">Fill in your task durations for the selected week.</small>
        
        <div class="row align-items-center mt-4">
            <div class="col-lg-4 text-right">
                <label class="font-weight-bold mb-0">Select Week:</label>
            </div>
            <div class="col-lg-8">
                <select class="form-control" data-placeholder="Pick a week" id="select_week">
                    <option></option>
                    <option selected>Feb 19 - Feb 23, 2026 (Current Week)</option>
                    <option>Feb 12 - Feb 16, 2026</option>
                    <option>Feb 05 - Feb 09, 2026</option>
                </select>
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

                    <div class="table-responsive">
                        <table class="table table-bordered timesheetTable" id="tab_logic">
                            
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 200px;">Task</th>
                                    <th class="text-center" style="width: 120px;">Mon</th>
                                    <th class="text-center" style="width: 120px;">Tue</th>
                                    <th class="text-center" style="width: 120px;">Wed</th>
                                    <th class="text-center" style="width: 120px;">Thu</th>
                                    <th class="text-center" style="width: 120px;">Fri</th>
                                    <th class="text-center" style="width: 120px;">Total</th>
                                    <th class="text-center" style="width: 60px;">Delete</th>
                                </tr>
                            </thead>

                            <tbody class="entry-group" id="addr0">

                                <tr class="data-row">
                                    <td class="text-center">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="height: 28px;"><i class="fa fa-tag"></i></span>
                                            </div>
                                            
                                            <input type="text" name="task_code[]" maxlength="10" class="form-control task-input" placeholder="Task ID">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="time-input-group">
                                            <div class="input-group">
                                                <input type="number" min="0" name='mon_m[]' placeholder='0' class="form-control text-center day-input mm-input" title="Minutes"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="time-input-group">
                                            <div class="input-group">
                                                <input type="number" min="0" name='tue_m[]' placeholder='0' class="form-control text-center day-input mm-input" title="Minutes"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="time-input-group">
                                            <div class="input-group">
                                                <input type="number" min="0" name='wed_m[]' placeholder='0' class="form-control text-center day-input mm-input" title="Minutes"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="time-input-group">
                                            <div class="input-group">
                                                <input type="number" min="0" name='thu_m[]' placeholder='0' class="form-control text-center day-input mm-input" title="Minutes"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="time-input-group">
                                            <div class="input-group">
                                                <input type="number" min="0" name='fri_m[]' placeholder='0' class="form-control text-center day-input mm-input" title="Minutes"/>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center row-total-box">
                                        <div class="row-total py-1">0h 00m</div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline btn-danger btn-xs delete-row-btn rounded-none"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                
                            </tbody>
                            
                            <tfoot>
                                <tr style="background-color: #f8fafc; font-weight: bold;">
                                    <td class="text-right">Daily Totals:</td>
                                    <td id="total_mon" class="text-center">0h 00m</td>
                                    <td id="total_tue" class="text-center">0h 00m</td>
                                    <td id="total_wed" class="text-center">0h 00m</td>
                                    <td id="total_thu" class="text-center">0h 00m</td>
                                    <td id="total_fri" class="text-center">0h 00m</td>
                                    <td id="grand_total" class="text-center text-primary" style="font-size: 1.1rem;">0h 00m</td>
                                    <td></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" id="add_row" class="btn btn-outline btn-primary btn-sm"><i class="fa fa-plus"></i> Add Row</button>
                    </div>

                </div>
            </div>
        
        </div>
    </div>

    <div class="footer-action">
        <div class="row">                   
            <div class="col-lg-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    <i class="fa fa-paper-plane mr-1"></i> Submit Timesheet
                </button>
            </div>             
        </div>
    </div>



@stop




@section('script-files')
    <!-- Select2 -->
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
@stop



@section('javascript')
    <script>
        $(document).ready(function(){
            // Initialize Select2
            $('#select_week').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            // Add Row
            $("#add_row").click(function(){
                var newEntry = $('.entry-group').first().clone();
                
                // Reset values
                newEntry.find('input').val('');
                newEntry.find('.row-total').text('0');              
                        
                // Append before footer
                $('#tab_logic tfoot').before(newEntry);
            });

            // Delete Row
            $(document).on('click', '.delete-row-btn', function(){
                if($('.entry-group').length > 1) {
                    $(this).closest('.entry-group').remove();
                    calculateAllTotals();
                } else {
                    alert('At least one entry is required.');
                }
            });

            // Calculate Totals on input change
            $(document).on('input', '.day-input', function(){
                calculateAllTotals();
            });

            function calculateAllTotals() {
                var grandTotalMinutes = 0;
                var dailyTotalMinutes = { 'mon': 0, 'tue': 0, 'wed': 0, 'thu': 0, 'fri': 0 };

                $('.data-row').each(function() {
                    var rowTotalMinutes = 0;
                    var days = ['mon', 'tue', 'wed', 'thu', 'fri'];
                    
                    var self = $(this);
                    days.forEach(function(day) {
                        var m = parseFloat(self.find('input[name="' + day + '_m[]"]').val()) || 0;
                        rowTotalMinutes += m;
                        dailyTotalMinutes[day] += m;
                    });
                    
                    self.find('.row-total').text(formatTime(rowTotalMinutes));
                    grandTotalMinutes += rowTotalMinutes;
                });

                $('#total_mon').text(formatTime(dailyTotalMinutes.mon));
                $('#total_tue').text(formatTime(dailyTotalMinutes.tue));
                $('#total_wed').text(formatTime(dailyTotalMinutes.wed));
                $('#total_thu').text(formatTime(dailyTotalMinutes.thu));
                $('#total_fri').text(formatTime(dailyTotalMinutes.fri));
                $('#grand_total').text(formatTime(grandTotalMinutes));
            }

            function formatTime(totalMinutes) {
                var hours = Math.floor(totalMinutes / 60);
                var minutes = totalMinutes % 60;
                return hours + "h " + (minutes < 10 ? "0" + minutes : minutes) + "m";
            }
        });
    </script>
@stop


