@extends('layouts.master', ['title' => 'User Table'])
@section('title','User Table')

@section('css-files')
    <link href="{{asset('admin/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/dataTables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2 class="mb-4">User Table</h2>
                    <div class="table-responsive">
                        <table class="display dataTable table-striped table-h-bordered _table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Image</th>
                                    <th>Gender</th>
                                    <th>Activated</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>john@example.com</td>
                                    <td>johndoe</td>
                                    <td><img src="https://via.placeholder.com/60" width="60px" alt=""></td>
                                    <td>Male</td>
                                    <td><span class="label label-primary">Activated</span></td>
                                    <td><span class="label label-primary">Active</span></td>
                                    <td class="text-right">-</td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>jane@example.com</td>
                                    <td>janesmith</td>
                                    <td><img src="https://via.placeholder.com/60" width="60px" alt=""></td>
                                    <td>Female</td>
                                    <td><span class="label label-warning">Not Activated</span></td>
                                    <td><span class="label label-disable">Inactive</span></td>
                                    <td class="text-right">-</td>
                                </tr>
                                <tr>
                                    <td>Alex Brown</td>
                                    <td>alex@example.com</td>
                                    <td>alexbrown</td>
                                    <td><img src="https://via.placeholder.com/60" width="60px" alt=""></td>
                                    <td>Other</td>
                                    <td><span class="label label-primary">Activated</span></td>
                                    <td><span class="label label-primary">Active</span></td>
                                    <td class="text-right">-</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Image</th>
                                    <th>Gender</th>
                                    <th>Activated</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script-files')
@stop

@section('javascript')
@stop 