@extends('layouts.master',['title' => 'Empty'])
@section('title','View Timesheet')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                   

            <div class="col-lg-3">
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Designation:</label>
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

            <div class="col-lg-3">
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select employee:</label>
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

            <div class="col-lg-4">
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Week:</label>
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

            <div class="col-lg-2">
                {{--                 
                <button type="submit" class="form-control btn btn-default btn-primary" style="float:right;">Submit</button>
                --}}
                <label class="font-weight-bold mb-1 mr-2">&nbsp;</label>
 
                <button type="submit" class="form-control btn btn-primary w-100">Submit</button>
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

                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 10; $i++)
                            <tr id='addr0'>
                                <td>PRJ1{{ $i }}</td>
                                <td>Task{{ $i }}</td>
                                <td>{{ $i }}0min</td>
                                <td>{{ $i }}0min</td>
                                <td>{{ $i }}0min</td>
                                <td>{{ $i }}0min</td>
                                <td>{{ $i }}0min</td>
                            </tr>
                            @endfor
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total Time</th>
                                <th>8:00</th>
                                <th>8:00</th>
                                <th>8:10</th>
                                <th>7:50</th>
                                <th>8:20</th>
                            </tr>
                        </tfoot>

                    </table>



                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-sm w-25">Approve</button>
                    </div>






                </div>
            </div>
        
        </div>
    </div>

    



       

    





                   

                    


@stop




@section('script-files')
    
@stop


@section('javascript')
<script>
    
</script>
@stop


