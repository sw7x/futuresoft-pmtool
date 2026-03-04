@extends('layouts.master')
@section('title','Project List')

@section('css-files')
    <!-- datatables -->
    <link href="{{asset('css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('page-css')
<style>
</style>    
@stop



@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- 
                    <div>
                        <button class="btn btn-primary btn-sm" type="button">
                            Add Project
                        </button>
                    </div> 
                    <div class="d-flex align-items-center">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Select Project:</label>
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
                    <table id="projects-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>///
                                <th>Project</th>///
                                <th>Client</th>///
                                <th>Locality</th>
                                <th>Status</th>
                                <th>Delivery Date</th>
                                <th>Progress</th>
                                <th>Action</th>//









                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 30; $i++)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>project{{ $i }} tgtggg tgtgg</td>
                                    <td>ABC Corp</td>
                                    <td>{{$i%2?'Local':'Foreign'}}</td>
                                <td>
                                    @if($i%2==0)
                                        <i class="fa fa-check-circle text-lg text-navy"></i>
                                    @else
                                        <i class="fa fa-times text-lg text-danger"></i>
                                    @endif

                                </td>
                                <td>2018/01/{{ str_pad(($i % 30) + 1, 2, '0', STR_PAD_LEFT) }}
                                <td>
                                    @if($i==0)
                                        <span class="badge badge-secondary px-3 py-2 uppercase tracking-wider">Not Started</span>
                                    @elseif($i==1)
                                        <span class="badge badge-info px-3 py-2 uppercase tracking-wider">In Progress</span>
                                    @elseif($i==2)
                                        <span class="badge badge-primary px-3 py-2 uppercase tracking-wider">Completed</span>
                                    @elseif($i==3)
                                        <span class="badge badge-warning px-3 py-2 uppercase tracking-wider">Blocked</span>
                                    @elseif($i==4)
                                        <span class="badge badge-danger px-3 py-2 uppercase tracking-wider">Cancelled</span>
                                    @else
                                        <span class="badge badge-info px-3 py-2 uppercase tracking-wider">In Progress</span>
                                        <span class="text-navy text-base font-bold"> - 72%</span>
                                    @endif</td>
                                
                                    
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
    <script src="{{asset('js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
@stop

@section('javascript')
<script>
    $(document).ready(function () {
        

        $('#projects-table').DataTable({
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
                    text: 'Add Project',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('projects.create')}}';

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
</script>
@stop

