@php    
    use App\Permissions\Abilities\StaticAbilities\AuthAbilities;
@endphp


@extends('core-module::layouts.master',['title' => 'Dashboard'])
@section('title','Dashboard')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')

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
                    [{{Sentinel::check()}}]

                    <div class="border mb-2" style="min-height: 50px;">
                        @can(AuthAbilities::CHANGE_PASSWORD)
                            <h3>CHANGE_PASSWORD</h3>
                        @endcan
                    </div>
                    
                    <div class="border mb-2" style="min-height: 50px;">
                        @can(AuthAbilities::LOGOUT)
                        <h3>LOGOUT</h3>
                        @endcan  
                    </div>                  

                    <div class="border mb-2" style="min-height: 50px;">
                        @can(AuthAbilities::LOGIN)
                        <h3>LOGIN</h3>
                        @endcan
                    </div>







                    <a href="{{URL::to('/')}}">/</a><br>
                    <a href="{{URL::to('/info')}}">/info</a><br>
                    <hr/>

                    <h2>messages</h2>
                    <a href="{{URL::to('/messages')}}">/mailbox</a><br>
                    <a href="{{URL::to('/messages/read-mail')}}">/read-mail</a><br>
                    <a href="{{URL::to('/messages/compose')}}">/compose</a><br>
                    <hr/>
                    
                    <h2>ETC</h2>
                    <a href="{{URL::to('/calendar')}}">/calendar</a><br>
                    <a href="{{URL::to('/profile')}}">/profile</a><br>
                    <a href="{{URL::to('/404')}}">/404</a><br>
                    <a href="{{URL::to('/login')}}">/login</a><br>
                    <hr/>


                    <h2>Users</h2>
                    <a href="{{URL::to('/users')}}">/users</a><br>
                    <a href="{{URL::to('/users/designations')}}">/designation-manage</a><br><br>
                    <hr/>

                    <h2>timesheet</h2>
                    <a href="{{URL::to('/timesheets')}}">/timesheet</a><br>
                    <a href="{{URL::to('/timesheets/view')}}">/timesheet/view</a><br>
                    <a href="{{URL::to('/timesheets/submit')}}">/timesheet/submit</a><br>
                    <hr/>

                    <h2>reports</h2>
                    <a href="{{URL::to('reports/designation-projectwise-timing')}}">reports/designation-projectwise-timing</a><br>
                    <a href="{{URL::to('reports/developer-projectwise-timing')}}">reports/developer-projectwise-timing</a><br>
                    <hr/>


                    <h2>project</h2>                    
                    <a href="{{URL::to('/projects')}}">project/</a><br>
                    <a href="{{URL::to('/projects/enroll-employees')}}">project/enroll-employees</a><br>
                    <a href="{{URL::to('/projects/invoices')}}">/projects/invoices</a><br>
                    <a href="{{URL::to('/projects/clients')}}">/projects/clients</a><br><br>
                    <hr/>
                    


                    <h2>threads</h2> 
                    <a href="{{URL::to('/threads/project')}}">/threads/project</a><br>
                    <a href="{{URL::to('/threads/task')}}">/threads/task</a><br>
                    <hr/>
                    


                    <h2>task</h2>
                    <a href="{{URL::to('/tasks/create')}}">/task/create</a><br>
                    <a href="{{URL::to('/tasks/view')}}">/task/view</a><br>
                    <a href="{{URL::to('/tasks/assign-developers')}}">/task/assign-developers</a><br><br>
                    



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


