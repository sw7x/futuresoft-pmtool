@extends('core-module::layouts.master',['title' => 'My Timesheet List'])
@section('title','My Timesheet List')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row justify-content-end">               

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

                    <table class="table table-bordered table-striped timesheetTable" id="wTimesheetTable">
                        <thead>
                        <tr >
                            <th class="text-center">Week</th>                            
                            <th class="text-center">Spend time</th>

                            <th class="text-center">Submit Date</th>
                            <th class="text-center">Approval Status</th>

                            <th class="text-center">Action</th>                           
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 9; $i++)
                            <tr id='addr0'>
                                <td>2025/1/5 - 2025/1/12</td>
                                <td>8 hours : 52 minutes</td>

                                <td>2025/1/15</td>
                                <td class="text-navy"><i class="fa fa-check-circle text-lg"></i> - <span class="text-xs font-semibold">2025/1/16</span></td>
                                <td>
                                    <a href="" class="btn-blue btn _btn-xs">View</a>                                            
                                </td>                               
                            </tr>







                            @endfor
                            <tr id='addr0'>
                                <td>2025/1/5 - 2025/1/12</td>
                                <td>8 hours : 52 minutes</td>

                                <td>2025/1/15</td>
                                <td class="text-warning"><i class="fa fa-warning text-lg"></i> - <span class="text-xs font-semibold">Pending</span></td>
                                <td>
                                    <a href="" class="btn-blue btn _btn-xs">View</a>                                            
                                </td>                               
                            </tr>
                        </tbody>                       
                    </table>                

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


