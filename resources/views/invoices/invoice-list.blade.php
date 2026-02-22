@extends('layouts.master')
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
                                        <th>Description</th>
                                        <th>Income/Cost</th>
                                        <th>Amount(Rs)</th>
                                        <th>Action</th>                                    
                                    </tr>
                                </thead>

                                <tbody>

                                @for ($i = 0; $i < 13; $i++)
                                    <tr>
                                        <td>{{$date[$i]}} - {{$i}}</td>
                                        <td>{{$description[$i]}}</td>
                                        <td>{{$pay[$i]}}</td>
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
                        window.location = '{{route('projects.invoices.create')}}';

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

