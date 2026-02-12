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
                    <div class="logo-element">
                        <small style="font-size: 65%">FutureSoft</small>
                    </div>
                </li>

                <li class="{{ Route::is('dashboard') ? 'active current' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa fa-desktop text-lg"></i> <span class="nav-label">Dashboard</span></a>
                </li>

                <li class="{{ \Str::is('projects.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('projects.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-pencil-square-o"></i> <span  class="nav-label">Project Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('projects.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('projects.list') ? 'current' : '' }}"><a href="{{ route('projects.list') }}"><i class="fa fa-list-ol"></i>Project list</a></li>
                        <li class="{{ Route::is('projects.enroll-employees') ? 'current' : '' }}"><a href="{{ route('projects.enroll-employees') }}"><i class="fa fa-users"></i>Enroll Developers</a></li>
                        <li class="{{ Route::is('projects.clients') ? 'current' : '' }}"><a href="{{ route('projects.clients') }}"><i class="fa  fa-street-view text-red"></i>Clients</a></li>
                        <li class="{{ Route::is('projects.invoices') ? 'current' : '' }}"><a href="{{ route('projects.invoices') }}"><i class="fa fa-dollar"></i>Invoices</a></li>
                    </ul>
                </li>            
    
                <li class="{{ \Str::is('threads.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('threads.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-files-o"></i> <span  class="nav-label">Threads</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('threads.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('threads.create') ? 'current' : '' }}"><a href="{{ route('threads.create') }}"><i class="fa fa-list-alt"></i>Create Thread</a></li>
                        
                        <li class="{{ Route::is('threads.projects') ? 'current' : '' }}"><a href="{{ route('threads.projects') }}"><i class="fa fa-list-alt"></i>Project Thread List</a></li>
                        <li class="{{ Route::is('threads.single-project') ? 'current' : '' }}"><a href="{{ route('threads.single-project',19) }}"><i class="fa fa-list-alt"></i>Single Project Thread</a></li>

                        <li class="{{ Route::is('threads.tasks') ? 'current' : '' }}"><a href="{{ route('threads.tasks') }}"><i class="fa fa-list-alt"></i>Task Thread List</a></li>
                        <li class="{{ Route::is('threads.single-task') ? 'current' : '' }}"><a href="{{ route('threads.single-task',21) }}"><i class="fa fa-list-alt"></i>Single Task Thread</a></li>


                    </ul>
                </li>                

                <li class="{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-tasks"></i> <span  class="nav-label">Task Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('tasks.create') ? 'current' : '' }}"><a href="{{ route('tasks.create') }}"><i class="fa fa-list"></i>Create Task Info</a></li>
                        <li class="{{ Route::is('tasks.view') ? 'current' : '' }}"><a href="{{ route('tasks.view') }}"><i class="fa fa-list"></i>view Task</a></li>
                        <li class="{{ Route::is('tasks.assign-developers') ? 'current' : '' }}"><a href="{{ route('tasks.assign-developers') }}"><i class="fa fa-user-plus"></i>Assign Developers</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('users.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('users.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-user"></i> <span  class="nav-label">User Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('users.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('users.index') ? 'current' : '' }}"><a href="{{ route('users.index') }}"><i class="fa fa-pencil-square-o"></i>User Accounts</a></li>
                        <li class="{{ Route::is('users.index') ? 'current' : '' }}"><a href="{{ route('users.index') }}"><i class="fa fa-pencil-square-o"></i>Create User ---</a></li>
                        
                        <li class="{{ Route::is('users.designations') ? 'current' : '' }}"><a href="{{ route('users.designations') }}"><i class="fa fa-bookmark"></i>Designations</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('reports.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('reports.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-hourglass-1"></i> <span  class="nav-label">Reports</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('reports.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('reports.designation-projectwise-timing') ? 'current' : '' }}"><a href="{{ route('reports.designation-projectwise-timing') }}"><i class="fa fa-clock-o"></i>Desig - Project wise timing</a></li>
                        <li class="{{ Route::is('reports.developer-projectwise-timing') ? 'current' : '' }}"><a href="{{ route('reports.developer-projectwise-timing') }}"><i class="fa fa-clock-o"></i>Dev - Project wise timing</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-hourglass-1"></i> <span  class="nav-label">timesheet</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('timesheets.pending-list') ? 'current' : '' }}"><a href="{{ route('timesheets.pending-list') }}"><i class="fa fa-list-ol"></i>Pending Timesheets</a></li>
                        <li class="{{ Route::is('timesheets.approved-list') ? 'current' : '' }}"><a href="{{ route('timesheets.approved-list') }}"><i class="fa fa-list-ol"></i>Approved Timesheets</a></li>
                        
                        <li class="{{ Route::is('timesheets.my-pending-list') ? 'current' : '' }}"><a href="{{ route('timesheets.my-pending-list') }}"><i class="fa fa-list-ol"></i>My Pending Timesheets</a></li>
                        <li class="{{ Route::is('timesheets.my-approved-list') ? 'current' : '' }}"><a href="{{ route('timesheets.my-approved-list') }}"><i class="fa fa-list-ol"></i>My Approved Timesheets</a></li>
                        

                        <li class="{{ Route::is('timesheets.create') ? 'current' : '' }}"><a href="{{ route('timesheets.create') }}"><i class="fa fa-calendar-check-o"></i>Create Timesheet</a></li>
                        <li class="{{ Route::is('timesheets.view') ? 'current' : '' }}"><a href="{{ route('timesheets.view') }}"><i class="fa fa-calendar"></i>View Timesheet</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('messages.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('messages.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-envelope"></i> <span  class="nav-label">Private Messages</span><span class="fa arrow ml-2"></span>
                        <span class="nav-label" ><span class="label label-primary pull-right">13</span></span>
                    </a>                    
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('messages.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('messages.index') ? 'current' : '' }}"><a href="{{ route('messages.index') }}"><i class="fa fa-inbox"></i>Inbox</a></li>
                        <li class="{{ Route::is('messages.compose') ? 'current' : '' }}"><a href="{{ route('messages.compose') }}"><i class="fa  fa-pencil"></i>Compose</a></li>
                        <li class="{{ Route::is('messages.read-mail') ? 'current' : '' }}"><a href="{{ route('messages.read-mail') }}"><i class="fa  fa-map-o"></i>Read</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('login') }}"><i class="fa fa-circle-o"></i> Login</a></li>
                        
                <li class="{{ Route::is('profile') ? 'active current' : '' }}">
                    <a href="{{ route('profile') }}">
                        <i class="fa fa-id-card text-lg"></i> <span class="nav-label">Profile</span>
                    </a>
                </li>            
                
            </ul>
        </div>
    </nav>







        
            

            