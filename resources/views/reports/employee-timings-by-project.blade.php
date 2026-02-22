@extends('layouts.master',['title' => 'Employee timings by project'])
@section('title','Employee timings by project')






@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        <h2 class="mb-4 font-bold text-muted">Select Project</h2>
        <div class="row">                   

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Client:<small>(optional)</small></label>
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
            
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Project:</label>
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
        <h2 class="mb-4 font-bold text-muted">Select Employee(ajax)</h2>
        <div class="row">                   

            <div class="col-lg-5">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Designation:</label>
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

            <div class="col-lg-5">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Employee:</label>
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

            <div class="col-lg-2">
                {{--                 
                <button type="submit" class="form-control btn btn-default btn-primary" style="float:right;">Submit</button>
                
                <label class="font-weight-bold mb-1 mr-2">&nbsp;</label>--}}
 
                <button type="submit" class="form-control btn btn-primary w-100">Submit</button>
            </div>             

        </div>     
    </div>


    


    <div class="row" id="_sortable-view">
        <div class="col-lg-12">

            <div class="ibox">
                <div class="ibox-content">
                    <h2 class="mb-4 font-bold text-muted">Employee ABC</h2>
                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Task</th>
                            <th class="text-center">Task Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Finished At</th>                            
                            <th class="text-center">Early<br/>submit</th>
                            <th class="text-center">Estimate<br/>time</th>
                            <th class="text-center">Spend<br/>time</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                            <tr id='addr0'>
                                <td>Task{{ $i }}</td>
                                <td>Employee total {{ $i % 2 !== 0 ? 'calculate' : 'draw css styles' }}...</td>
                                <td>{{ $i % 2 !== 0 ? 'Done' : 'Pending' }}</td>
                                <td>{{ $i % 2 !== 0 ? '2025/1/5 10:52 AM' : '' }}</td>
                                <td><i class="text-2xl fa {{ $i % 2 !== 0 ? 'fa-check-circle text-navy' : 'fa-times-circle text-danger' }}"></i></td>
                                <td>30 minutes</td>
                                <td>20 minutes</td>                                                               
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total Hours:</th>
                                <th>88 hours 12 Minutes</th>
                                <th>98 hours 10 Minutes</th>
                            </tr>
                        </tfoot>
                    </table>                

                </div>
            </div>
        
        </div>
    </div>

    <div class="row" id="_sortable-view">
        <div class="col-lg-12">

            <div class="ibox">
                <div class="ibox-content">
                    <h2 class="mb-4 font-bold text-muted">Employee ABC</h2>
                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Task</th>
                            <th class="text-center">Task Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Finished At</th>                            
                            <th class="text-center">Early<br/>submit</th>
                            <th class="text-center">Estimate<br/>time</th>
                            <th class="text-center">Spend<br/>time</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                            <tr id='addr0'>
                                <td>Task{{ $i }}</td>
                                <td>Employee total {{ $i % 2 !== 0 ? 'calculate' : 'draw css styles' }}...</td>
                                <td>{{ $i % 2 !== 0 ? 'Done' : 'Pending' }}</td>
                                <td>{{ $i % 2 !== 0 ? '2025/1/5 10:52 AM' : '' }}</td>
                                <td><i class="text-2xl fa {{ $i % 2 !== 0 ? 'fa-check-circle text-navy' : 'fa-times-circle text-danger' }}"></i></td>
                                <td>30 minutes</td>
                                <td>20 minutes</td>                                                               
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total Hours:</th>
                                <th>88 hours 12 Minutes</th>
                                <th>98 hours 10 Minutes</th>
                            </tr>
                        </tfoot>
                    </table>                

                </div>
            </div>
        
        </div>
    </div>

    <div class="row" id="_sortable-view">
        <div class="col-lg-12">

            <div class="ibox">
                <div class="ibox-content">
                    <h2 class="mb-4 font-bold text-muted">Employee ABC</h2>
                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Task</th>
                            <th class="text-center">Task Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Finished At</th>                            
                            <th class="text-center">Early<br/>submit</th>
                            <th class="text-center">Estimate<br/>time</th>
                            <th class="text-center">Spend<br/>time</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                            <tr id='addr0'>
                                <td>Task{{ $i }}</td>
                                <td>Employee total {{ $i % 2 !== 0 ? 'calculate' : 'draw css styles' }}...</td>
                                <td>{{ $i % 2 !== 0 ? 'Done' : 'Pending' }}</td>
                                <td>{{ $i % 2 !== 0 ? '2025/1/5 10:52 AM' : '' }}</td>
                                <td><i class="text-2xl fa {{ $i % 2 !== 0 ? 'fa-check-circle text-navy' : 'fa-times-circle text-danger' }}"></i></td>
                                <td>30 minutes</td>
                                <td>20 minutes</td>                                                               
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total Hours:</th>
                                <th>88 hours 12 Minutes</th>
                                <th>98 hours 10 Minutes</th>
                            </tr>
                        </tfoot>
                    </table>                

                </div>
            </div>
        
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Project threads pagination" class="my-3">
        <ul class="pagination justify-content-end mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>

@stop




@section('script-files')
    
@stop


@section('javascript')
<script>
    
</script>
@stop
