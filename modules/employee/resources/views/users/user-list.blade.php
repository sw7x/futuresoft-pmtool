@extends('core-module::layouts.master',['title' => 'Empty'])
@section('title','Users')




@section('css-files')
    <link href="{{asset('css/plugins/switchery/switchery.css')}}" rel="stylesheet">

    <!-- datatables -->
    <link href="{{asset('css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/dataTables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    <!-- Magnific Popup core CSS file -->
    <link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">


    <!-- sweetalert2 CSS file-->
    <link rel="stylesheet" href="{{asset('css/plugins/sweetalert2/sweetalert2.min.css')}}">

    <!-- toastr CSS file-->
    <link rel="stylesheet" href="{{asset('css/plugins/toastr/toastr.min.css')}}">    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')

    @php
        // Demo data for user tables if not provided by controller
        if (!isset($owners)) {
            $owners = [];
            for ($i = 1; $i <= 2; $i++) {
                $owners[] = [
                    'data' => [
                        'id'          => $i,
                        'fullName'    => "Owner {$i} Example",
                        'email'       => "owner{$i}@example.com",
                        'phone'       => '+94 77 0000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'username'    => "owner{$i}",
                        'profilePic'  => '',
                        'gender'      => $i % 2 === 0 ? 'Female' : 'Male',
                        'isActivated' => true,
                        'status'      => true,
                    ],
                ];
            }
        }

        if (!isset($managers)) {
            $managers = [];
            for ($i = 1; $i <= 4; $i++) {
                $managers[] = [
                    'data' => [
                        'id'          => 100 + $i,
                        'fullName'    => "Manager {$i} Example",
                        'email'       => "manager{$i}@example.com",
                        'phone'       => '+94 77 1000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'username'    => "manager{$i}",
                        'gender'      => $i % 2 === 0 ? 'Female' : 'Male',
                        'isActivated' => true,
                        'status'      => true,
                    ],
                ];
            }
        }

        if (!isset($pms)) {
            $pms = [];
            for ($i = 1; $i <= 20; $i++) {
                $pms[] = [
                    'data' => [
                        'id'          => 200 + $i,
                        'fullName'    => "Project Manager {$i}",
                        'email'       => "pm{$i}@example.com",
                        'phone'       => '+94 77 200' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'username'    => "pm{$i}",
                        'gender'      => $i % 2 === 0 ? 'Female' : 'Male',
                        'isActivated' => $i % 3 !== 0,
                        'status'      => $i % 5 !== 0,
                    ],
                ];
            }
        }

        if (!isset($developers)) {
            $developers = [];
            for ($i = 1; $i <= 30; $i++) {
                $developers[] = [
                    'data' => [
                        'id'          => 300 + $i,
                        'fullName'    => "Developer {$i}",
                        'email'       => "dev{$i}@example.com",
                        'phone'       => '+94 77 300' . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'username'    => "dev{$i}",
                        'gender'      => $i % 2 === 0 ? 'Female' : 'Male',
                        'isActivated' => $i % 4 !== 0,
                        'status'      => $i % 6 !== 0,
                    ],
                ];
            }
        }
    @endphp

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
                    <div class="tabs-container mb-5">

                        <ul class="nav nav-tabs" role="tablist" id="tab-button-wrapper">                                
                            <li><a class="nav-link __active" data-toggle="tab" href="#tab-owners">Owners</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#tab-managers">Managers</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#tab-pms">Project Managers</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#tab-developers">Developers</a></li>                                                           
                        </ul>

                        <div class="tab-content" id="tab-pane-wrapper">                                
                                
                            <div role="tabpanel" id="tab-owners" class="tab-pane __active">
                                <div class="panel-body">
                                    
                                        @if(isset($owners) && is_array($owners))
                                            @if(!empty($owners))                                                    
                                                <div class="table-responsive">
                                                    <table id="user-list-owner" class="display dataTable table-striped table-h-bordered _table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Image</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/Disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($owners as $item)                                                                
                                                                <tr class="owner_{{$item['data']['id']}}">
                                                                    <td>{{$item['data']['fullName']}}</td>
                                                                    <td>{{$item['data']['email']}}<br> {{$item['data']['phone']}}</td>
                                                                    <td>{{$item['data']['username']}}</td>

                                                                    <td>
                                                                        @if($item['data']['profilePic'] != '')
                                                                            <a class="no-clickable popup-img effect" href="{{$item['data']['profilePic']}}" data-effect="mfp-zoom-in">
                                                                                <img src="{{$item['data']['profilePic']}}" width="100px" alt="">
                                                                            </a>
                                                                        @endif
                                                                    </td>

                                                                    <td>{{$item['data']['gender']}}</td>

                                                                    <td>
                                                                        @if(array_key_exists("isActivated", $item['data']))
                                                                            @if($item['data']['isActivated'] === true)
                                                                                <span class="label label-primary">Activated</span>
                                                                            @else
                                                                                <span class="label label-warning">Not Activated</span>
                                                                            @endif
                                                                        @else
                                                                            <span class="label">error</span>
                                                                        @endif
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        {{-- @can(UserManageAbilities::CHANGE_USERS_STATUS) --}}
                                                                            {{-- <input type="checkbox" class="js-switch-owner" userId="{{$item['data']['id']}}" {{($item['data']['status'] === true)?'checked':''}}/> --}}
                                                                        {{-- @else --}}
                                                                            @if($item['data']['status'] === true)
                                                                                <span class="label label-primary">Active</span>
                                                                            @else
                                                                                <span class="label label-disable">Inactive</span>
                                                                            @endif
                                                                        {{-- @endcan --}}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <div class="btn-group">
                                                                            {{-- @can(UserManageAbilities::ADMIN_PANEL_VIEW_USER, $item['dbRec']) 
                                                                                <a href="{{route ('admin.users.show',$item['data']['id'])}}" class="btn-white btn btn-xs">View</a>--}}
                                                                                <a href="" class="btn-white btn btn-xs">View</a>
                                                                            {{-- @endcan                                                                     --}}
                                                                            
                                                                            {{-- @can(UserManageAbilities::VIEW_EDIT_PAGE)
                                                                                <a href="{{route ('admin.users.edit',$item['data']['id'])}}" class="btn btn-blue btn-xs">Edit</a> --}}
                                                                                <a href="" class="btn btn-blue btn-xs">Edit</a>
                                                                            {{-- @endcan --}}
                                                                            
                                                                            {{-- @can(UserManageAbilities::DELETE_USERS) --}}
                                                                                <a  href="javascript:void(0);"
                                                                                    data-user-id="{{$item['data']['id']}}"
                                                                                    class="remove-user-btn btn-warning btn btn-xs">Trash</a>
                                                                            {{-- @endcan --}}
                                                                        </div>
                                                                        {{-- @can(UserManageAbilities::DELETE_USERS)
                                                                            <form class="user-remove" action="{{ route('admin.users.destroy', $item['data']['id']) }}" method="POST"> --}}
                                                                            <form class="user-remove" action="" method="POST">
                                                                                @method('DELETE')
                                                                                <input name="userId" type="hidden" value="{{$item['data']['id']}}">
                                                                                <input name="userType" type="hidden" value="owner">
                                                                                @csrf
                                                                            </form>
                                                                        {{-- @endcan     --}}
                                                                    </td>
                                                                </tr>
                                                                
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Image</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/Disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>                                                     
                                            @else
                                                <x-flash-message 
                                                    class="flash-info mt-3"  
                                                    title="No Owners!" 
                                                    message="" 
                                                    :canClose="false"/>
                                            @endif
                                        @else
                                            <x-flash-message 
                                                class="flash-danger mt-3" 
                                                title="Data not available!" 
                                                message="Owner records are not available or not in correct format" 
                                                :canClose="false"/>
                                        @endif
                                                                  
                                </div>
                            </div>
                            <!--  -->

                            
                            <div role="tabpanel" id="tab-managers" class="tab-pane">
                                <div class="panel-body">
                                    
                                        @if(isset($managers) && is_array($managers))
                                            @if(!empty($managers))     
                                                <div class="table-responsive">
                                                    <table id="user-list-manager" class="display dataTable table-striped table-h-bordered _table-hover" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Email<br>
                                                                phone</th>
                                                            <th>Username</th>
                                                            <th>Gender</th>
                                                            <th>Activated</th>
                                                            <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                            <th class="text-right">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($managers as $item)                                                                
                                                                <tr class="manager_{{$item['data']['id']}}">
                                                                    <td>{{$item['data']['fullName']}}</td>
                                                                    <td>{{$item['data']['email']}}<br> {{$item['data']['phone']}}</td>
                                                                    <td>{{$item['data']['username']}}</td>
                                                                    <td>{{$item['data']['gender']}}</td>

                                                                    <td>
                                                                        @if(array_key_exists("isActivated", $item['data']))
                                                                            @if($item['data']['isActivated'] === true)
                                                                                <span class="label label-primary">Activated</span>
                                                                            @else
                                                                                <span class="label label-warning">Not Activated</span>
                                                                            @endif
                                                                        @else
                                                                            <span class="label">error</span>
                                                                        @endif
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        {{-- @can(UserManageAbilities::CHANGE_USERS_STATUS) --}}
                                                                            {{-- <input type="checkbox" class="js-switch-owner" userId="{{$item['data']['id']}}" {{($item['data']['status'] === true)?'checked':''}}/> --}}
                                                                        {{-- @else --}}
                                                                            @if($item['data']['status'] === true)
                                                                                <span class="label label-primary">Active</span>
                                                                            @else
                                                                                <span class="label label-disable">Inactive</span>
                                                                            @endif
                                                                        {{-- @endcan --}}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        <div class="btn-group">
                                                                            {{-- @can(UserManageAbilities::ADMIN_PANEL_VIEW_USER, $item['dbRec'])
                                                                                <a href="{{route ('admin.users.show',$item['data']['id'])}}" class="btn-white btn btn-xs">View</a> --}}
                                                                                <a href="" class="btn-white btn btn-xs">View</a>
                                                                            {{-- @endcan --}}

                                                                            {{-- @can(UserManageAbilities::VIEW_EDIT_PAGE)
                                                                                <a href="{{route ('admin.users.edit',$item['data']['id'])}}" class="btn btn-blue btn-xs">Edit</a> --}}
                                                                                <a href="" class="btn btn-blue btn-xs">Edit</a>
                                                                            {{-- @endcan --}}
                                                                            
                                                                            {{-- @can(UserManageAbilities::DELETE_USERS) --}}
                                                                                <a  href="javascript:void(0);"
                                                                                    data-user-id="{{$item['data']['id']}}"
                                                                                    class="remove-user-btn btn-warning btn btn-xs">Trash</a>
                                                                            {{-- @endcan --}}
                                                                        </div>
                                                                        {{-- @can(UserManageAbilities::DELETE_USERS)
                                                                            <form class="user-remove" action="{{ route('admin.users.destroy', $item['data']['id']) }}" method="POST"> --}}
                                                                            <form class="user-remove" action="" method="POST">
                                                                                @method('DELETE')
                                                                                <input name="userId" type="hidden" value="{{$item['data']['id']}}">
                                                                                <input name="userType" type="hidden" value="manager">
                                                                                @csrf
                                                                            </form>
                                                                        {{-- @endcan --}}
                                                                    </td>
                                                                </tr>                                                                
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>                                            
                                            @else
                                                <x-flash-message 
                                                    class="flash-info mt-3"  
                                                    title="No Students!" 
                                                    message="" 
                                                    :canClose="false"/>
                                            @endif
                                        @else
                                            <x-flash-message 
                                                class="flash-danger mt-3" 
                                                title="Data not available!" 
                                                message="Student records are not available or not in correct format" 
                                                :canClose="false"/>
                                        @endif
                                    
                                </div>
                            </div>                                    
                            <!--  -->


                            <div role="tabpanel" id="tab-pms" class="tab-pane">
                                <div class="panel-body">
                                    
                                        @if(isset($pms) && is_array($pms))
                                            @if(!empty($pms))    
                                                <div class="table-responsive">
                                                    <table id="user-list-pm" class="display dataTable table-striped table-h-bordered _table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($pms as $item)                                                            
                                                            <tr class="pm_{{$item['data']['id']}}">
                                                                <td>{{$item['data']['fullName']}}</td>
                                                                <td>{{$item['data']['email']}}<br> {{$item['data']['phone']}}</td>
                                                                <td>{{$item['data']['username']}}</td>
                                                                <td>{{$item['data']['gender']}}</td>

                                                                <td>
                                                                    @if(array_key_exists("isActivated", $item['data']))
                                                                        @if($item['data']['isActivated'] === true)
                                                                            <span class="label label-primary">Activated</span>
                                                                        @else
                                                                            <span class="label label-warning">Not Activated</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="label">error</span>
                                                                    @endif
                                                                </td>

                                                                <td>
                                                                    {{-- @can(UserManageAbilities::CHANGE_USERS_STATUS) --}}
                                                                        {{-- <input type="checkbox" class="js-switch-owner" userId="{{$item['data']['id']}}" {{($item['data']['status'] === true)?'checked':''}}/> --}}
                                                                    {{-- @else --}}
                                                                        @if($item['data']['status'] === true)
                                                                            <span class="label label-primary">Active</span>
                                                                        @else
                                                                            <span class="label label-disable">Inactive</span>
                                                                        @endif
                                                                    {{-- @endcan --}}
                                                                </td>

                                                                <td class="text-right">
                                                                    <div class="btn-group">
                                                                        {{-- @can(UserManageAbilities::ADMIN_PANEL_VIEW_USER, $item['dbRec'])
                                                                            <a href="{{route ('admin.users.show',$item['data']['id'])}}" class="btn-white btn btn-xs">View</a> --}}
                                                                            <a href="" class="btn-white btn btn-xs">View</a>
                                                                        {{-- @endcan --}}
                                                                        
                                                                        {{-- @can(UserManageAbilities::VIEW_EDIT_PAGE)
                                                                            <a href="{{route ('admin.users.edit',$item['data']['id'])}}" class="btn btn-blue btn-xs">Edit</a> --}}
                                                                            <a href="" class="btn btn-blue btn-xs">Edit</a>
                                                                        {{-- @endcan --}}

                                                                        {{-- @can(UserManageAbilities::DELETE_USERS) --}}
                                                                            <a  href="javascript:void(0);"
                                                                                data-user-id="{{$item['data']['id']}}" 
                                                                                class="remove-user-btn btn-warning btn btn-xs">Trash</a>
                                                                        {{-- @endcan --}}
                                                                    </div>
                                                                    {{-- @can(UserManageAbilities::DELETE_USERS)
                                                                        <form class="user-remove" action="{{ route('admin.users.destroy', $item['data']['id']) }}" method="POST"> --}}
                                                                        <form class="user-remove" action="" method="POST">
                                                                            @method('DELETE')
                                                                            <input name="userId" type="hidden" value="{{$item['data']['id']}}">
                                                                            <input name="userType" type="hidden" value="pm">
                                                                            @csrf
                                                                        </form>
                                                                    {{-- @endcan --}}
                                                                </td>
                                                            </tr>                                                            
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>                                            
                                            @else
                                                <x-flash-message 
                                                    class="flash-info mt-3"  
                                                    title="No Marketers!" 
                                                    message="" 
                                                    :canClose="false"/>
                                            @endif
                                        @else
                                            <x-flash-message 
                                                class="flash-danger mt-3" 
                                                title="Data not available!" 
                                                message="Marketer records are not available or not in correct format" 
                                                :canClose="false"/>
                                        @endif
                                    
                                </div>
                            </div>
                            <!--  -->


                            <div role="tabpanel" id="tab-developers" class="tab-pane">
                                <div class="panel-body">
                                    
                                        @if(isset($developers) && is_array($developers))
                                            @if(!empty($developers))    
                                                <div class="table-responsive">
                                                    <table id="user-list-developer" class="display dataTable table-striped table-h-bordered _table-hover" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($developers as $item)                                                                
                                                            <tr class="developers_{{$item['data']['id']}}">
                                                                <td>{{$item['data']['fullName']}}</td>
                                                                <td>{{$item['data']['email']}}<br> {{$item['data']['phone']}}</td>
                                                                <td>{{$item['data']['username']}}</td>
                                                                <td>{{$item['data']['gender']}}</td>

                                                                <td>
                                                                    @if(array_key_exists("isActivated", $item['data']))
                                                                        @if($item['data']['isActivated'] === true)
                                                                            <span class="label label-primary">Activated</span>
                                                                        @else
                                                                            <span class="label label-warning">Not Activated</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="label">error</span>
                                                                    @endif
                                                                </td>
                                                                
                                                                <td>
                                                                    {{-- @can(UserManageAbilities::CHANGE_USERS_STATUS) --}}
                                                                        {{-- <input type="checkbox" class="js-switch-owner" userId="{{$item['data']['id']}}" {{($item['data']['status'] === true)?'checked':''}}/> --}}
                                                                    {{-- @else --}}
                                                                        @if($item['data']['status'] === true)
                                                                            <span class="label label-primary">Active</span>
                                                                        @else
                                                                            <span class="label label-disable">Inactive</span>
                                                                        @endif
                                                                    {{-- @endcan --}}
                                                                </td>

                                                                <td class="text-right">
                                                                    <div class="btn-group">
                                                                        {{-- @can(UserManageAbilities::ADMIN_PANEL_VIEW_USER, $item['dbRec'])
                                                                            <a href="{{route ('admin.users.show',$item['data']['id'])}}" class="btn-white btn btn-xs">View</a> --}}
                                                                            <a href="" class="btn-white btn btn-xs">View</a>
                                                                        {{-- @endcan --}}
                                                                        
                                                                        {{-- @can(UserManageAbilities::VIEW_EDIT_PAGE)
                                                                        <a href="{{route ('admin.users.edit',$item['data']['id'])}}" class="btn btn-blue btn-xs">Edit</a> --}}
                                                                        <a href="" class="btn btn-blue btn-xs">Edit</a>
                                                                        {{-- @endcan --}}
                                                                        
                                                                        {{-- @can(UserManageAbilities::DELETE_USERS) --}}
                                                                            <a  href="javascript:void(0);"
                                                                                data-user-id="{{$item['data']['id']}}"
                                                                                class="remove-user-btn btn-warning btn btn-xs">Trash</a>
                                                                        {{-- @endcan --}}
                                                                    </div>
                                                                    {{-- @can(UserManageAbilities::DELETE_USERS)
                                                                        <form class="user-remove" action="{{ route('admin.users.destroy', $item['data']['id']) }}" method="POST"> --}}
                                                                        <form class="user-remove" action="" method="POST">
                                                                            @method('DELETE')
                                                                            <input name="userId" type="hidden" value="{{$item['data']['id']}}">
                                                                            <input name="userType" type="hidden" value="developer">
                                                                            @csrf
                                                                        </form>
                                                                    {{-- @endcan --}}
                                                                </td>
                                                            </tr>                                                            
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email<br>
                                                                    phone</th>
                                                                <th>Username</th>
                                                                <th>Gender</th>
                                                                <th>Activated</th>
                                                                <th>status<br/> <small>Enable/disable <br/> by admin</small></th>
                                                                <th class="text-right">Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>                                           
                                            @else
                                                <x-flash-message 
                                                    class="flash-info mt-3"  
                                                    title="No Editors!" 
                                                    message="" 
                                                    :canClose="false"/>
                                            @endif
                                        @else
                                            <x-flash-message 
                                                class="flash-danger mt-3" 
                                                title="Data not available!" 
                                                message="Editor records are not available or not in correct format" 
                                                :canClose="false"/>
                                        @endif
                                    
                                </div>
                            </div>                                       
                            <!--  -->

                        
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>





@stop




@section('script-files')
    <script src="{{asset('js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Switchery -->
    <script src="{{asset('js/plugins/switchery/switchery.js')}}"></script>

    <!-- Magnific Popup core JS file -->
    <script src="{{asset('js/jquery.magnific-popup.min.js')}}"></script>


    <!-- sweetalert2 js file-->
    <script src="{{asset('js/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <!-- toastr js file-->
    <script src="{{asset('js/plugins/toastr/toastr.min.js')}}"></script>    
@stop


@section('javascript')
<script>
    $(document).ready(function ($) {

        let selectedTab = window.location.hash;
        //selectedTab = (selectedTab=='')?'#tab-owners':selectedTab;

        let firstTabId   = '#' + $('#tab-pane-wrapper .tab-pane').first().attr('id');
        selectedTab      = (selectedTab=='')? firstTabId : selectedTab;

        console.log(selectedTab);
        $('.nav-link[href="' + selectedTab + '"]' ).trigger('click');
        //window.location.hash = selectedTab;

        //Prevent default hash behavior on page load
        window.scrollTo(0,0);
    });



    $(document).ready(function() {

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            //"positionClass": "toast-top-full-width",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };


        $('.remove-user-btn').on('click', function(event){
            var userId   = $(this).data('user-id');
            var form     = $(this).parent().parent().find('form.user-remove');

            Swal.fire({
                title: 'Move course to trash',
                text: "Are you sure you want to move this course to trash?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3fcc98',
                confirmButtonText: 'Trash'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        
                        //todo
                        {{-- url: "{{route('admin.users.check-can-delete')}}", --}}
                        url: "",
                        

                        type: "post",
                        async:true,
                        dataType:'json',
                        data:{
                            _token : '{{ csrf_token() }}',
                            userId : userId
                        },
                        success: function (response) {
                            if(response.status === 'success'){

                                if(response.canDelete === true){
                                    // course content is empty - submit form
                                    form.submit();
                                
                                }else{
                                    
                                    // course content is empty
                                    Swal.fire({
                                        title: 'Cannot move this user to trash',
                                        text: "User already have related child table recods (coupons, course selections)",
                                        icon: 'warning',
                                        confirmButtonColor: '#3fcc98',
                                    });                                

                                }
                            }else if(response.status == 'error'){
                                toastr[response.status](response.message);
                            }
                        },
                        error:function(request,errorType,errorMessage)
                        {
                            //alert ('error - '+errorType+'with message - '+errorMessage);
                            //toastr["success"]("User updated successfully! ", "Good Job!")
                            toastr["error"]("User already have related child table recods (coupons, course selections)!");
                        }
                    });                  
                    
                }
            });

            event.preventDefault();
        });



        $('a.popup-img').magnificPopup({
            type: 'image',
            closeBtnInside: true,
            closeOnContentClick: true,
            tLoading: '', // remove text from preloader
            fixedContentPos : false,
            /* don't add this part, it's just to disable cache on image and test loading indicator */
            callbacks: {
                beforeChange: function() {
                    this.items[0].src = this.items[0].src + '?=' + Math.random();
                },
                beforeOpen: function() {
                    this.st.mainClass = this.st.el.attr('data-effect');
                },
                open: function() {
                    jQuery('body').addClass('noscroll');
                },
                close: function() {
                    jQuery('body').removeClass('noscroll');
                }
            },
            //removalDelay: 500, //delay removal by X to allow out-animation
            mainClass: 'mfp-with-fade',
        });



        $('#user-list-owner').DataTable({
            paging: true,
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
            {{-- @can(UserManageAbilities::VIEW_CREATE_PAGE) --}}
                {
                    text: 'Add owner',
                    href : 'uuu',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('users.create').'#tab-owners'}}';
                        {{-- alert( 'Button activated' ); --}}
                    },
                    className: 'add-ct mb-3 btn-green '
                }
            {{-- @endcan --}}
            ],
            "columnDefs": [{
                "targets": [1,2,3,4,5,6,7],
                "searchable": false

            }],
            fnDrawCallback:function (oSettings) {
                console.log("after table create");
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-owner'));

                elems.forEach(function(html) {
                    //need to check that it has not already be instantiated.
                    if(!html.getAttribute('data-switchery')){
                        var switchery = new Switchery(html, { size: 'small' });
                    }
                    html.onchange = function () {
                        console.log("on click");
                        var checked = html.checked;
                        var id = $(html).attr('userId');
                        if (checked == false) {
                            checked = 2;
                        } else {
                            checked = 1;
                        }
                        //todo
                        changeUserState(id, checked);
                    }
                });
            }
        });

        $('#user-list-manager').DataTable({
            paging: true,
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
            {{-- @can(UserManageAbilities::VIEW_CREATE_PAGE) --}}
                {
                    text: 'Add manager',
                    href : 'uuu',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('users.create').'#tab-managers'}}';
                        {{-- alert( 'Button activated' ); --}}
                    },
                    className: 'add-ct mb-3 btn-green '
                }
            {{-- @endcan --}}
            ],
            "columnDefs": [{
                "targets": [1,2,3,4,5,6],                
                "searchable": false

            }],
            fnDrawCallback:function (oSettings) {
                console.log("after table create");
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-manager'));

                elems.forEach(function(html) {
                    //need to check that it has not already be instantiated.
                    if(!html.getAttribute('data-switchery')){
                        var switchery = new Switchery(html, { size: 'small' });
                    }
                    html.onchange = function () {
                        console.log("on click");
                        var checked = html.checked;
                        var id = $(html).attr('userId');

                        if (checked == false) {
                            checked = 2;
                        } else {
                            checked = 1;
                        }
                        //todo
                        changeUserState(id, checked);
                    }
                });
            }
        });

        $('#user-list-pm').DataTable({
            paging: true,
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
            {{-- @can(UserManageAbilities::VIEW_CREATE_PAGE) --}}
                {
                    text: 'Add Project Manager',
                    href : 'uuu',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('users.create').'#tab-pms'}}';
                        {{-- alert( 'Button activated' ); --}}
                    },
                    className: 'add-ct mb-3 btn-green '
                }
            {{-- @endcan --}}
            ],
            "columnDefs": [{
                "targets": [1,2,3,4,5,6],
                "searchable": false

            }],
            fnDrawCallback:function (oSettings) {
                console.log("after table create");
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-pm'));

                elems.forEach(function(html) {
                    //need to check that it has not already be instantiated.
                    if(!html.getAttribute('data-switchery')){
                        var switchery = new Switchery(html, { size: 'small' });
                    }
                    html.onchange = function () {
                        console.log("on click");
                        var checked = html.checked;
                        var id = $(html).attr('userId');
                        if (checked == false) {
                            checked = 2;
                        } else {
                            checked = 1;
                        }
                        //todo
                        changeUserState(id, checked);
                    }
                });
            }
        });


        $('#user-list-developer').DataTable({
            paging: true,
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
            {{-- @can(UserManageAbilities::VIEW_CREATE_PAGE) --}}
                {
                    text: 'Add Developer',
                    href : 'uuu',
                    action: function ( e, dt, node, config ) {
                        window.location = '{{route('users.create').'#tab-developers'}}';
                        {{--  alert( 'Button activated' ); --}}

                    },
                    className: 'add-ct mb-3 btn-green '
                }
            {{-- @endcan --}}
            ],
            "columnDefs": [{
                "targets": [1,2,3,4,5,6],
                "searchable": false

            }],
            fnDrawCallback:function (oSettings) {
                console.log("after table create");
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-developer'));

                elems.forEach(function(html) {
                    //need to check that it has not already be instantiated.
                    if(!html.getAttribute('data-switchery')){
                        var switchery = new Switchery(html, { size: 'small' });
                    }
                    html.onchange = function () {
                        console.log("on click");
                        var checked = html.checked;
                        var id = $(html).attr('userId');
                        if (checked == false) {
                            checked = 2;
                        } else {
                            checked = 1;
                        }
                        //todo
                        changeUserState(id, checked);
                    }
                });
            }
        });


        function changeUserState(id, checked){
            //alert(id);
            //alert(checked);
            //if switch in off state, before change checked = 1  then checked = 2
            //if switch in on  state, before change checked = 2  then checked = 1
            var status;
            if(checked === 1){
                status = 1;
            }else if(checked === 2){
                status = 0;
            }else{
                status = 0;
            }

            $.ajax({
                url: "",//TODO
                type: "post",
                async:true,
                dataType:'json',
                data:{
                    status : status,
                    _token : '{{ csrf_token() }}',
                    userId : id
                },
                success: function (response) {
                    toastr[response.status](response.message,response.msgTitle);
                    // You will get response from your PHP page (what you echo or print)
                },
                error:function(request,errorType,errorMessage)
                {
                    //alert ('error - '+errorType+'with message - '+errorMessage);
                    //toastr["success"]("User updated successfully! ", "Good Job!")
                    toastr["error"]("User status update failed!")
                }
            });
        }

    });    
</script>
@stop


