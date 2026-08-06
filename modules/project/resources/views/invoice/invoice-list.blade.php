@extends('core-module::layouts.master')
@section('title','Invoice List')

@section('css-files')
    <!-- datatables -->
    <link href="{{asset('css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('page-css')
<style>
    /* Make input + button equal height (Calculate Cost) */
    .invoice-cost-group .form-control,
    .invoice-cost-group .btn {
        height: 38px;
    }

    .invoice-cost-group .btn {
        line-height: 1;
        display: inline-flex;
        align-items: center;
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
        flex: 1 1 calc(33% - 15px);
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
        <div class="row">                   

            <div class="col-lg-8 ml-auto">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Select Project:</label>
                    </div>

                    <div class="col-lg-9">
                        <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                            <option></option>
                            <option>Alaska</option>
                            <option>California</option>
                            <option>Delaware</option>
                            <option>Tennessee</option>
                            <option>Texas</option>
                            <option>Washington</option>
                        </select>
                    </div>
                </div>                        
            </div>             

        </div>
    </div>

    <div class="ibox-content m-b-sm border-bottom">

        <h2 class="m-0 font-bold text-dark">Summary</h2>


        <div class="info-group">
            <!-- Total Costs -->
            <div class="info-badge">
                <i class="fa fa-credit-card" style="color: #e53e3e;"></i>
                <div>
                    <span class="info-label">Total Costs</span>
                    <span class="info-value">$12,450</span>
                </div>
            </div>

            <!-- Total Income -->
            <div class="info-badge">
                <i class="fa fa-university" style="color: #38a169;"></i>
                <div>
                    <span class="info-label">Total Income</span>
                    <span class="info-value">$18,000</span>
                </div>
            </div>

            <!-- Profit -->
            <div class="info-badge">
                <i class="fa fa-line-chart" style="color: #667eea;"></i>
                <div>
                    <span class="info-label">Profit</span>
                    <span class="info-value">$5,550</span>
                </div>
            </div>


        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">                                       
                <div class="ibox-content">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- 
                        <div>
                            <button class="btn btn-primary btn-sm" type="button">
                                Add Invoice
                            </button>
                        </div> 
                        <div class="d-flex align-items-center">
                            <label for="project-select" class="font-weight-bold mb-0 mr-2">Select Invoice:</label>
                            <select id="project-select" class="form-control input-sm" style="width: 260px;">
                                <option value="">Select a project</option>
                                <option value="prj-0">project 0 tgtggg tgtgg</option>
                                <option value="prj-1">project 1 tgtggg tgtgg</option>
                                <option value="prj-2">project 2 tgtggg tgtgg</option>
                            </select>
                        </div>
                        --}}
                    </div>

                    






                    <div class="table-responsive m-t-sm">
                        <?php

                            $date = array('2018/1/3','2018/1/20','2018/1/22','2018/1/25','2018/2/11','2018/2/12','2018/2/20','2018/2/22','2018/3/11','2018/3/28','2018/3/11','2018/2/11','2018/2/12');

                            $description = array('advanced payment','pay for 1st stage','pay for 2nd stage','shuteshock images','pay for 3rd stage','','','','','','','11','12','13');

                            $pay    = array('income','income','income','cost','income','cost','cost','income','income','cost','income','income','cost','cost');

                            $amount = array('10,000','20,000','100,000','10,000','15,000','5,000','1,000','20,000','30,000','1,000','20,000','20,000','120,000','130,000');
                            ?>


                            <table id="invoices-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Income/Cost</th>
                                        <th>Description</th>                                        
                                        <th>Amount(Rs)</th>
                                        <th>Action</th>                                    
                                    </tr>
                                </thead>

                                <tbody>

                                @for ($i = 0; $i < 13; $i++)
                                    <tr>                                        
                                        <td class="font-semibold text-sm uppercase" style="{{ $pay[$i] == 'cost' ? 'color: red;' : ($pay[$i] == 'income' ? 'color: green;' : '') }}">
                                            {{ $pay[$i] }}
                                        </td>
                                        <td>{{$date[$i]}} - {{$i}}</td>
                                        <td>{{$description[$i]}}</td>                                        
                                        <td>{{$amount[$i]}}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="" class="btn-white btn btn-xs">View</a>
                                                <a href="" class="btn btn-blue btn-xs">Edit</a>
                                                <a href="javascript:void(0);" class="remove-subject-btn btn-warning btn btn-xs">Remove</a>
                                            </div>                                                
                                            <form class="subject-remove" action="" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </td>
                                        
                                    </tr>
                                @endfor


                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>8:00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>

                            </table>           
                    </div>               

                </div>
            </div>
        </div>
    </div>




    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                  

            <div class="col-lg-4 ml-auto">
                <div class="input-group invoice-cost-group">
                    <input type="text" class="form-control" placeholder="" readonly/>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="button">Calculate Cost</button>
                    </div>
                </div>
            </div>     

        </div>
    </div>
@stop










@section('script-files')
    <script src="{{asset('js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
@stop

@section('javascript')
<script>
    $(document).ready(function () {
        

        $('#invoices-table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            dom: 'Bfrtip',



            "pageLength": 10,
            //lengthChange: true,
            //searching: true,
            //ordering: true,
            //info: true,
            //paging: true,
            
            //pagingType: 'simple_numbers', // Previous 1 2 3 Next
            //dom: 'lfrtip', // length, filter (search), table, pagination


            buttons: [
                {
                    text: 'Add Invoice',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('invoices.create')}}';

                    },
                    className: 'add-ct mb-3 btn-green '
                }
            ],
            "columnDefs": [{
                "targets": [2],
                "searchable": true
            }]

        });


        






    });



/*

           

*/









</script>
@stop

