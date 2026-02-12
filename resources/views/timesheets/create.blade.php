@extends('layouts.master',['title' => 'Empty'])
@section('title','Submit Timesheet')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                   

            <div class="col-lg-8 ml-auto">
                <div class="row">
                    <div class="col-lg-3">
                        <label for="project-select" class="font-weight-bold mb-0 mr-2">Select Week:</label>
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
                    


                    


                        <table class="table table-bordered table-hover timesheetTable" id="tab_logic">
                            <thead>
                            <tr >
                                <th class="text-center">Project</th>
                                <th class="text-center">Task</th>
                                <th class="text-center">Monday(min)</th>
                                <th class="text-center">Tuesday(min)</th>
                                <th class="text-center">Wednesday(min)</th>
                                <th class="text-center">Thursday(min)</th>
                                <th class="text-center">Friday(min)</th>

                            </tr>
                            </thead>
                            <tbody>


                            <tr id='addr0'>
                                <td style="width: 240px;">
                                    <select class="form-control select2 proj-select" style="width: 100%;">
                                        <option></option>
                                        <option>PRJ-1010</option>
                                        <option>PRJ-122</option>
                                        <option>PRJ-126</option>
                                        <option>PRJ-124</option>
                                        <option>PRJ-125</option>
                                    </select>
                                </td>
                                <td style="width: 240px;">
                                    <select class="form-control select2 task-select" style="width: 100%;">
                                        <option></option>
                                        <option>Task-1010-11</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </td>
                                <td><input type="text" name='name0'  placeholder='' class="form-control"/></td>
                                <td><input type="text" name='name0'  placeholder='' class="form-control"/></td>
                                <td><input type="text" name='name0'  placeholder='' class="form-control"/></td>
                                <td><input type="text" name='name0'  placeholder='' class="form-control"/></td>
                                <td><input type="text" name='name0'  placeholder='' class="form-control"/></td>
                            </tr>

                            </tbody>
                        </table>
                        
                        <div class="text-right mt-3">
                            <button id="add_row" class="btn btn-primary">Add Row</button>
                            <button id="delete_row" class="btn btn-danger">Delete Row</button>
                        </div>



                </div>
            </div>
        
        </div>
    </div>

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                   

            <div class="col-lg-12 text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
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


