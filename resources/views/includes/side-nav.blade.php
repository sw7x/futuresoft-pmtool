@php    
    /*use App\Permissions\Abilities\ContactUsAbilities;
    use App\Permissions\Abilities\SubjectAbilities;
    use App\Permissions\Abilities\SettingsAbilities;
    use App\Permissions\Abilities\UserManageAbilities;
    use App\Permissions\Abilities\CourseAbilities;*/    
@endphp

    <nav class="sidebar navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">


                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <img alt="image" class="_bg-white _rounded-circle" src="{{asset('images/logo.png')}}"/>                        
                        @if(Sentinel::check())
                            <a class="dropdown-toggle" href="#">
                                {{-- 
                                <span class="mt-1 text-white text-center text-lg __text-muted text-xs block">{{optional($currentUser)->username}}</span>
                                --}}
                                <span class="mt-1 text-white text-center text-lg __text-muted text-xs block">username</span>
                            </a>

                            <div class="text-center">
                            {{--                                 
                            <small class="text-white">( {{$currentUserRole}} )</small>
                            --}}                                
                            <small class="text-white">( currentUserRole )</small>

                            </div>                        
                        @endif                       
                    </div>
                    <div class="logo-element"><small>FutureSoft</small></div>
                </li>

                <li class="active">
                    <a href=""><i class="fa fa-desktop text-lg"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                            
                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-pencil-square-o"></i> <span  class="nav-label">Project Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li class=""><a href=""><i class="fa fa-list-ol"></i>Project list</a></li>
                        <li><a href=""><i class="fa fa-users"></i>Enroll Developers</a></li>
                        <li><a href=""><i class="fa  fa-street-view text-red"></i> <span>Client Management</span></a></li>
                    </ul>
                </li>


                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-files-o"></i> <span  class="nav-label">Threads</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li><a href=""><i class="fa fa-list-alt"></i>Project Threads</a></li>
                        <li><a href=""><i class="fa fa-list-alt"></i>Task Threads</a></li>
                    </ul>
                </li>


                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-tasks"></i> <span  class="nav-label">Task Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li><a href=""><i class="fa fa-list"></i>Manage Task Info</a></li>
                        <li><a href=""><i class="fa fa-list"></i>view/Submit Task</a></li>
                        <li><a href=""><i class="fa fa-user-plus"></i>Assign Developers</a></li>
                    </ul>
                </li>



                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-user"></i> <span  class="nav-label">User Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li><a href=""><i class="fa fa-pencil-square-o"></i>User Accounts</a></li>
                        <li><a href=""><i class="fa fa-bookmark"></i>Manage Designations</a></li>
                    </ul>
                </li>


                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-hourglass-1"></i> <span  class="nav-label">Reports</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li><a href=""><i class="fa fa-clock-o"></i>Desig - Project wise timing</a></li>
                        <li><a href=""><i class="fa fa-clock-o"></i>Dev - Project wise timing</a></li>
                        <li><a href=""><i class="fa  fa-calendar-check-o"></i>Submit Timesheet</a></li>
                        <li><a href=""><i class="fa fa-calendar"></i>View Timesheet</a></li>
                    </ul>
                </li>

                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-dollar"></i> <span  class="nav-label">Cost Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li><a href=""><i class="fa fa-balance-scale"></i>project Invoices</a></li>
                    </ul>
                </li>

                <li class="">
                    <a href="#" class="" aria-expanded='false'>
                        <i class="fa fa-envelope"></i> <span  class="nav-label">Private Messages</span><span class="fa arrow ml-2"></span>
                        <span class="nav-label" >
                            <span class="label label-primary pull-right">13</span>
                        </span>
                    </a>
                    
                    <ul class="nav nav-second-level collapse" aria-expanded='false'>
                        <li>
                            <a href=""><i class="fa fa-inbox"></i>Inbox
                                
                            </a>
                        </li>
                        <li><a href=""><i class="fa  fa-pencil"></i>Compose</a></li>
                        <li class=""><a href=""><i class="fa  fa-map-o"></i>Read</a></li>
                    </ul>
                </li>          

                <li><a href=""><i class="fa fa-circle-o"></i> Login</a></li>
        
                

                <li class="">
                    <a href="">
                        <i class="fa fa-id-card text-lg"></i> <span class="nav-label">Profile</span>
                    </a>
                </li>
                
                
            
            </ul>
        </div>
    </nav>



    {{-- Project Management --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li class=""><a href="{{ route('project.list') }}"><i class="fa fa-list-ol"></i>Project list</a></li>
    <li><a href="{{ route('project.enroll-developers') }}"><i class="fa fa-users"></i>Enroll Developers</a></li>
    <li><a href="{{ route('client.management') }}"><i class="fa  fa-street-view text-red"></i> <span>Client Management</span></a></li>
</ul>

{{-- Threads --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li><a href="{{ route('threads.project') }}"><i class="fa fa-list-alt"></i>Project Threads</a></li>
    <li><a href="{{ route('threads.task') }}"><i class="fa fa-list-alt"></i>Task Threads</a></li>
</ul>

{{-- Task Management --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li><a href="{{ route('task.manage-info') }}"><i class="fa fa-list"></i>Manage Task Info</a></li>
    <li><a href="{{ route('task.view-submit') }}"><i class="fa fa-list"></i>view/Submit Task</a></li>
    <li><a href="{{ route('task.assign-developers') }}"><i class="fa fa-user-plus"></i>Assign Developers</a></li>
</ul>

{{-- User Management --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li><a href="{{ route('users.accounts') }}"><i class="fa fa-pencil-square-o"></i>User Accounts</a></li>
    <li><a href="{{ route('designations.manage') }}"><i class="fa fa-bookmark"></i>Manage Designations</a></li>
</ul>

{{-- Reports --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li><a href="{{ route('reports.designation-projectwise-timing') }}"><i class="fa fa-clock-o"></i>Desig - Project wise timing</a></li>
    <li><a href="{{ route('reports.developer-projectwise-timing') }}"><i class="fa fa-clock-o"></i>Dev - Project wise timing</a></li>
    <li><a href="{{ route('timesheet.submit') }}"><i class="fa  fa-calendar-check-o"></i>Submit Timesheet</a></li>
    <li><a href="{{ route('timesheet.view') }}"><i class="fa fa-calendar"></i>View Timesheet</a></li>
</ul>

{{-- Cost Management --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li><a href="{{ route('cost.project-invoices') }}"><i class="fa fa-balance-scale"></i>project Invoices</a></li>
</ul>

{{-- Private Messages --}}
<ul class="nav nav-second-level collapse" aria-expanded='false'>
    <li>
        <a href="{{ route('messages.inbox') }}"><i class="fa fa-inbox"></i>Inbox</a>
    </li>
    <li><a href="{{ route('messages.compose') }}"><i class="fa  fa-pencil"></i>Compose</a></li>
    <li class=""><a href="{{ route('messages.read') }}"><i class="fa  fa-map-o"></i>Read</a></li>
</ul>

{{-- Login + Profile --}}
<li><a href="{{ route('login.demo') }}"><i class="fa fa-circle-o"></i> Login</a></li>
<li class="">
    <a href="{{ route('profile.page') }}">
        <i class="fa fa-id-card text-lg"></i> <span class="nav-label">Profile</span>
    </a>
</li>



        
            

            