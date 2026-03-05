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
                                <span class="mt-1 text-white text-center text-lg __text-muted text-xs block">{{optional($currentUser)->username}}</span>
                                {{--
                                <span class="mt-1 text-white text-center text-lg __text-muted text-xs block">username</span>
                                --}}
                            </a>

                            <div class="text-center">                                                           
                            <small class="text-white">( {{$currentUserRole}} )</small>                                                         
                            {{--  <small class="text-white">( currentUserRole )</small>--}}  
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
                        <i class="fa fa-code"></i> <span  class="nav-label">Project Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('projects.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        
                        <li class="{{ Route::is('projects.list') ? 'current' : '' }}"><a href="{{ route('projects.list') }}"><i class="fa fa-list-ol"></i>Project list</a></li>
                        <li class="{{ Route::is('projects.create') ? 'current' : '' }}"><a href="{{ route('projects.create') }}"><i class="fa fa-plus-square"></i>Create project</a></li>
                        <li class="{{ Route::is('projects.single') ? 'current' : '' }}"><a href="{{ route('projects.single',12) }}"><i class="fa fa-folder-open"></i>Single project</a></li>

                        
                        <li class="{{ Route::is('projects.clients.list') ? 'current' : '' }}"><a href="{{ route('projects.clients.list') }}"><i class="fa  fa-street-view text-red"></i>Clients</a></li>
                        <li class="{{ Route::is('projects.clients.create') ? 'current' : '' }}"><a href="{{ route('projects.clients.create') }}"><i class="fa fa-user-plus"></i>Create client</a></li>
                        <li class="{{ Route::is('projects.clients.single') ? 'current' : '' }}"><a href="{{ route('projects.clients.single',3) }}"><i class="fa fa-user"></i>Single client</a></li>

                        <li class="{{ Route::is('projects.invoices.list') ? 'current' : '' }}"><a href="{{ route('projects.invoices.list') }}"><i class="fa fa-dollar"></i>Invoices</a></li>
                        <li class="{{ Route::is('projects.invoices.create') ? 'current' : '' }}"><a href="{{ route('projects.invoices.create') }}"><i class="fa fa-file-text-o"></i>Create invoice</a></li>
                        <li class="{{ Route::is('projects.invoices.single') ? 'current' : '' }}"><a href="{{ route('projects.invoices.single',5) }}"><i class="fa fa-file-text"></i>Single invoice</a></li>

                        <li class="{{ Route::is('projects.enroll-employees') ? 'current' : '' }}"><a href="{{ route('projects.enroll-employees') }}"><i class="fa fa-users"></i>Enroll developers</a></li>
                        <li class="{{ Route::is('projects.timeline') ? 'current' : '' }}"><a href="{{ route('projects.timeline') }}"><i class="fa fa-calendar"></i>Project timeline</a></li>

                    </ul>
                </li>            
    
                <li class="{{ \Str::is('threads.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('threads.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-files-o"></i> <span  class="nav-label">Threads</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('threads.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('threads.create') ? 'current' : '' }}"><a href="{{ route('threads.create') }}"><i class="fa fa-plus"></i>Create thread</a></li>
                        
                        <li class="{{ Route::is('threads.projects') ? 'current' : '' }}"><a href="{{ route('threads.projects') }}"><i class="fa fa-comments"></i>Project thread list</a></li>
                        <li class="{{ Route::is('threads.single-project') ? 'current' : '' }}"><a href="{{ route('threads.single-project',19) }}"><i class="fa fa-comment"></i>Project thread</a></li>

                        <li class="{{ Route::is('threads.tasks') ? 'current' : '' }}"><a href="{{ route('threads.tasks') }}"><i class="fa fa-comments-o"></i>Task thread list</a></li>
                        <li class="{{ Route::is('threads.single-task') ? 'current' : '' }}"><a href="{{ route('threads.single-task',21) }}"><i class="fa fa-comment-o"></i>Task thread</a></li>


                    </ul>
                </li>                

                <li class="{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-tasks"></i> <span  class="nav-label">Task Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('tasks.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('tasks.manage') ? 'current' : '' }}"><a href="{{ route('tasks.manage') }}"><i class="fa fa-sitemap"></i>Manage tasks</a></li>
                        <li class="{{ Route::is('tasks.view') ? 'current' : '' }}"><a href="{{ route('tasks.view') }}"><i class="fa fa-list-ul"></i>View tasks</a></li>
                        <li class="{{ Route::is('tasks.assign-developers') ? 'current' : '' }}"><a href="{{ route('tasks.assign-developers') }}"><i class="fa fa-users"></i>Assign developers</a></li>
                        <li class="{{ Route::is('tasks.view-single') ? 'current' : '' }}"><a href="{{ route('tasks.view-single',7) }}"><i class="fa fa-eye"></i>View single task</a></li>
                        <li class="{{ Route::is('tasks.edit-single') ? 'current' : '' }}"><a href="{{ route('tasks.edit-single',7) }}"><i class="fa fa-edit"></i>Edit/Submit single task</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('users.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('users.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-user"></i> <span  class="nav-label">User Management</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('users.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('users.index') ? 'current' : '' }}"><a href="{{ route('users.index') }}"><i class="fa fa-users"></i>User accounts</a></li>
                        <li class="{{ Route::is('users.create') ? 'current' : '' }}"><a href="{{ route('users.create') }}"><i class="fa fa-user-plus"></i>Create user</a></li>

                        <li class="{{ Route::is('users.view-single') ? 'current' : '' }}"><a href="{{ route('users.view-single',7) }}"><i class="fa fa-user-plus"></i>View user</a></li>
                        
                        <li class="{{ Route::is('users.manage-designations') ? 'current' : '' }}"><a href="{{ route('users.manage-designations') }}"><i class="fa fa-sitemap"></i>Manage designations</a></li>
                        <li class="{{ Route::is('users.view-designations') ? 'current' : '' }}"><a href="{{ route('users.view-designations') }}"><i class="fa fa-address-card-o"></i>View designations</a></li>
                        <li class="{{ Route::is('users.assign-designations') ? 'current' : '' }}"><a href="{{ route('users.assign-designations') }}"><i class="fa fa-id-badge"></i>Assign designations</a></li>
                    </ul>
                </li>

                <li class="{{ \Str::is('reports.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('reports.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-bar-chart"></i> <span  class="nav-label">Reports</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('reports.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('reports.project-timings-by-designation') ? 'current' : '' }}">
                            <a class="_text-xs" title='Designation Project wise timing' href="{{ route('reports.project-timings-by-designation') }}">
                                <i class="fa fa-sitemap"></i> <small class="text-xs font-semibold">Project timings by designation</small>
                            </a>
                        </li>
                        <li class="{{ Route::is('reports.designation-timings-by-project') ? 'current' : '' }}">
                            <a class="_text-xs" title='Developer Project wise timing' href="{{ route('reports.designation-timings-by-project') }}">
                                <i class="fa fa-briefcase"></i> <small class="text-xs font-semibold">Designation timings by project</small>
                            </a>
                        </li>
                        <li class="{{ Route::is('reports.project-timings-by-employee') ? 'current' : '' }}">
                            <a class="_text-xs" title='Employee Project wise timing' href="{{ route('reports.project-timings-by-employee') }}">
                                <i class="fa fa-users"></i> <small class="text-xs font-semibold">Project timings by employee</small>
                            </a>
                        </li>
                        <li class="{{ Route::is('reports.employee-timings-by-project') ? 'current' : '' }}">
                            <a class="_text-xs" title='Employee Project wise timing' href="{{ route('reports.employee-timings-by-project') }}">
                                <i class="fa fa-tasks"></i> <small class="text-xs font-semibold">Employee timings by project</small>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'active current' : '' }}">
                    <a href="#" class="" aria-expanded='{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-hourglass-1"></i> <span  class="nav-label">timesheet</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" aria-expanded='{{ \Str::is('timesheets.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <li class="{{ Route::is('timesheets.manager-timesheet-list') ? 'current' : '' }}"><a href="{{ route('timesheets.manager-timesheet-list') }}"><i class="fa fa-list-alt"></i>Manager timesheet list</a></li>
                        
                        <li class="{{ Route::is('timesheets.my-timesheet-list') ? 'current' : '' }}">
                            <a href="{{ route('timesheets.my-timesheet-list') }}">
                                <i class="fa fa-user-o"></i>My timesheet list <small class="text-yellow-200 italic text-xs font-semibold">(Dev/PM)</small>
                            </a>
                        </li>

                        <li class="{{ Route::is('timesheets.create') ? 'current' : '' }}"><a href="{{ route('timesheets.create') }}"><i class="fa fa-calendar-plus-o"></i>Create timesheets</a></li>
                        <li class="{{ Route::is('timesheets.view') ? 'current' : '' }}"><a href="{{ route('timesheets.view') }}"><i class="fa fa-calendar"></i>View timesheets</a></li>
                        <li class="{{ Route::is('timesheets.approve') ? 'current' : '' }}"><a href="{{ route('timesheets.approve') }}"><i class="fa fa-check-square-o"></i>Approve timesheet</a></li>
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

                <li class="{{ \Str::is('settings.*', Route::currentRouteName()) ? 'active current' : '' }}">                
                    <a href="#" class="" aria-expanded='{{ \Str::is('settings.*', Route::currentRouteName()) ? 'true' : 'false' }}'>
                        <i class="fa fa-wrench"></i><span  class="nav-label">Settings</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse in" aria-expanded="true" style="">
                        <li class="{{ Route::is('settings.general') ? 'current' : '' }}"><a href="{{ route('settings.general') }}"><i class="fa fa-cog"></i>General - Settings</a></li>
                        <li class="{{ Route::is('settings.advanced') ? 'current' : '' }}"><a href="{{ route('settings.advanced') }}"><i class="fa fa-cogs"></i>Advanced - Settings</a></li>
                    </ul>
                </li>
                
                




                <li><a href="{{ route('auth.login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                        
                <li class="{{ Route::is('profile') ? 'active current' : '' }}">
                    <a href="{{ route('profile') }}">
                        <i class="fa fa-id-card text-lg"></i> <span class="nav-label">Profile</span>
                    </a>
                </li>            
                
            </ul>
        </div>
    </nav>







        
            

            