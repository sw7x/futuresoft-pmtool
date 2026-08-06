@extends('core-module::layouts.master')
@section('title','Client List')

@section('css-files')
    <!-- datatables -->
    <link href="{{asset('css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('page-css')
<style>
</style>    
@stop

@php
    $clientArr = [
        'Mahaweli Hotel',
        'kandy resort',
        'Lakbima',
        'AXN phone shop',
        'SIBA campus',
        'Rangiri aqua',
        'Kingsland Hotel',
        'ganga addara hotel',
        'gampola school',
        'CMB hotel',
        'TravaBlue'
    ];
@endphp

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- 
                    <div>
                        <button class="btn btn-primary btn-sm" type="button">
                            Add Client
                        </button>
                    </div> 
                    <div class="d-flex align-items-center">
                        <label for="client-select" class="font-weight-bold mb-0 mr-2">Select Client:</label>
                        <select id="client-select" class="form-control input-sm" style="width: 260px;">
                            <option value="">Select a client</option>
                            <option value="prj-0">client 0 tgtggg tgtgg</option>
                            <option value="prj-1">client 1 tgtggg tgtgg</option>
                            <option value="prj-2">client 2 tgtggg tgtgg</option>
                        </select>
                    </div>
                    --}}
                </div>

                <div class="table-responsive m-t-sm">
                    <table id="clients-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Project Count</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 11; $i++)
                                <tr>
                                    <td>{{$clientArr[$i]}}</td>
                                    <td>cleint description  {{ $i }}</td>
                                    <td>abcdddddd{{ $i }}@gmail.com</td>
                                    <td>121212121</td>
                                    <th>{{$i+2}}</th>
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
                    </table>                    
                </div>

            </div>
        </div>
    </div>
</div>
@stop






@section('script-files')






@section('script-files')
    <script src="{{asset('js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
@stop

@section('javascript')
<script>
    $(document).ready(function () {
        

        $('#clients-table').DataTable({
            "paging": true,
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
                    text: 'Add Client',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('clients.create')}}';
                    },
                    className: 'add-ct mb-3 btn-green '
                }
            ],
            "columnDefs": [{
                "targets": [2],
                "searchable": true
            }]

        });


        var i=1;
        $(".add-phone-number").click(function(event){

            var newRow    = $('.phoneNumbers-form-group:first').clone();
            var textfield = newRow.find( '#phone0' ).attr('id', 'phone' + i);

            textfield.attr('id', 'phone' + i);
            textfield.val('');


            $('.phoneNumbersDiv').append(newRow);

            i++;
            event.preventDefault();
        });


        $(document).on("click",".delete-tp-number",function(event) {
            if($('.phoneNumbers-form-group').length > 1){
                $(this).closest('.phoneNumbers-form-group').remove();
            }
            event.preventDefault();
        });


        var j=1;
        $(".add-email").click(function(event){

            var newRow    = $('.email-form-group:first').clone();
            var textfield = newRow.find( '#email0' ).attr('id', 'email' + j);

            textfield.attr('id', 'email' + j);
            textfield.val('');


            $('.EmailDiv').append(newRow);

            j++;
            event.preventDefault();
        });


        $(document).on("click",".delete-email",function(event) {
            if($('.email-form-group').length > 1){
                $(this).closest('.email-form-group').remove();
            }
            event.preventDefault();
        });



    });



/*

           

*/









</script>
@stop

