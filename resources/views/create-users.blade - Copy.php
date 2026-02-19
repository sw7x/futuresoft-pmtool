@extends('layouts.master',['title' => 'Create Users'])
@section('title','Create Users')

@section('css-files')

    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <!-- bootstrap datapicker -->
    <link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('plugins/summernote-0.8.18/summernote-bs4.css')}}">
    <!-- <link href="css/plugins/summernote/summernote-bs4.css" rel="stylesheet">-->

    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">

    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond-plugin-image-preview.min.css')}}">
    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond.min.css')}}">

@stop


@section('content')
    <div class="row" id="">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">
                    <div class="tabs-container">
                        {{-- 
                        @canany([
                            UserManageAbilities::CREATE_PROJECT_MANAGERS,
                            UserManageAbilities::CREATE_DEVELOPERS,
                            UserManageAbilities::CREATE_MANAGERS,
                            UserManageAbilities::CREATE_OWNER
                        ]) 
                        --}}
                            <ul class="nav nav-tabs" role="tablist">                            
                                {{-- @can(UserManageAbilities::CREATE_PROJECT_MANAGERS) --}}
                                    <li><a class="nav-link active" data-toggle="tab" href="#tab-project-managers">Add project managers</a></li>
                                {{-- @endcan --}}
                                
                                {{-- @can(UserManageAbilities::CREATE_DEVELOPERS) --}}
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-developers">Add developers</a></li>
                                {{-- @endcan --}}
                                
                                {{-- @can(UserManageAbilities::CREATE_MANAGERS) --}}
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-managers">Add managers</a></li>
                                {{-- @endcan --}}
                                
                                {{-- @can(UserManageAbilities::CREATE_OWNER) --}}
                                    <li><a class="nav-link" data-toggle="tab" href="#tab-owners">Add Owners</a></li>
                                {{-- @endcan --}}
                            </ul>

                            <div class="tab-content mb-3">
                                {{-- @can(UserManageAbilities::CREATE_PROJECT_MANAGERS) --}}
                                    <div role="tabpanel" id="tab-project-managers" class="tab-pane active">
                                        <div class="panel-body">                                    
                                            @foreach ($errors->all() as $error)
                                                {{-- $error --}}
                                            @endforeach                                    

                                            @if(Session::has('pm_add_message'))
                                                <x-flash-message  
                                                    :class="Session::get('pm_add_cls', 'flash-info')"  
                                                    :title="Session::get('pm_add_msgTitle') ?? 'Info!'" 
                                                    :message="Session::get('pm_add_message') ?? 'Info!'"  
                                                    :message2="Session::get('pm_add_message2') ?? ''"  
                                                    :canClose="true" />
                                            @endif

                                            <form class="" id="" action="" method="post">
                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Name <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="pm_name" class="form-control" required value="{{ old('pm_name') }}">
                                                        @if ($errors->has('pm_name'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_name') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Username</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="pm_uname" class="form-control" value="{{ old('pm_uname') }}">
                                                        <small>Leave blank if you want to auto generate username</small><br>
                                                        <small>Only aplha numeric charaters allowed (no spaces, no special characters)</small>
                                                        @if (Session::get('is_pm_usernameFill')=='y' && $errors->has('pm_uname'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_uname') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="qemail" name="pm_email" class="form-control" required value="{{ old('pm_email') }}">
                                                        @if ($errors->has('pm_email'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_email') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Phone <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" name="pm_phone" class="form-control" required value="{{ old('pm_phone') }}">
                                                        @if ($errors->has('pm_phone'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_phone') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Password <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8 password-container">
                                                        <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                                               name="pm_password" maxlength="12" minlength="6" required value="{{ old('pm_password') }}"/>
                                                        <button type="button" id="btnToggle" class="pw-toggle" style="right: 20px;">
                                                            <i id="eyeIcon" class="fa fa-eye"></i>
                                                        </button>
                                                        @if ($errors->has('pm_password'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_password') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Year of birth</label>
                                                    <div class="col-sm-8 input-group date">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="pm_birth_year">
                                                        <div class="w-full">
                                                            @if ($errors->has('pm_birth_year'))
                                                                <ul class="mt-1">
                                                                    @foreach ($errors->get('pm_birth_year') as $error)
                                                                        <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-4 col-form-label">Education qualifications</label>
                                                    <div class="col-sm-8">
                                                        <div class="border border-edu">
                                                            <textarea rows="3" class="form-control" name="pm_edu_details">{{ old('pm_edu_details') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-4 col-form-label">Profile image</label>
                                                    <div class="col-sm-8">
                                                        <input type="file"
                                                               class="filepond-img pm_profile_img"
                                                               name="pm_profile_img"
                                                               accept="image/webp, image/png, image/jpeg, image/gif"
                                                               data-max-file-size="1MB"/>
                                                        <p>Image size : 500x500</p>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Gender <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b" required id="pm_gender" name="pm_gender" value="{{ old('pm_gender') }}">
                                                            <option></option>
                                                            <option {{ old("pm_gender") == 'male' ? "selected":"" }} value="male">Male</option>
                                                            <option {{ old("pm_gender") == 'female' ? "selected":"" }} value="female">Female</option>
                                                            <option {{ old("pm_gender") == 'other' ? "selected":"" }} value="other">Other</option>
                                                        </select>
                                                        @if ($errors->has('pm_gender'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('pm_gender') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Submit status</label>
                                                    <div class="col-sm-8">
                                                        <div class="i-checks">
                                                            <label> <input {{  old('pm_stat') == "enable" ? "checked" : (old('pm_stat') =="disable" ? "" : "checked") }}
                                                                           type="radio" checked value="enable" name="pm_stat"> <i></i> Enable </label>
                                                        </div>
                                                        <div class="i-checks">
                                                            <label> <input {{  old('pm_stat') == "disable" ? "checked" : "" }}
                                                                           type="radio" value="disable" name="pm_stat"> <i></i> Disable </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                {{csrf_field ()}}
                                                <div class="form-group row">
                                                    <div class="col-sm-4 offset-sm-4">
                                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                                        <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                {{-- @endcan --}}

                                {{-- @can(UserManageAbilities::CREATE_DEVELOPERS) --}}
                                    <div role="tabpanel" id="tab-developers" class="tab-pane">
                                        <div class="panel-body">
                                            @foreach ($errors->all() as $error)
                                                {{-- $error --}}
                                            @endforeach

                                            @if(Session::has('developer_add_message'))
                                                <x-flash-message  
                                                    :class="Session::get('developer_add_cls', 'flash-info')"  
                                                    :title="Session::get('developer_add_msgTitle') ?? 'Info!'" 
                                                    :message="Session::get('developer_add_message') ?? 'Info!'"  
                                                    :message2="Session::get('developer_add_message2') ?? ''"  
                                                    :canClose="true" />
                                            @endif

                                            <form class="" id="" action="" method="post">
                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Name <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="dev_name" class="form-control" required value="{{ old('dev_name')}}">
                                                        @if ($errors->has('dev_name'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_name') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>


                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Username</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="dev_uname" class="form-control" value="{{ old('dev_uname')}}">
                                                        <small>Leave blank if you want to auto generate username</small><br>
                                                        <small>Only aplha numeric charaters allowed (no spaces, no special characters)</small>
                                                        @if (Session::get('is_developer_usernameFill')=='y' && $errors->has('dev_uname'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_uname') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="email" name="dev_email" class="form-control" required value="{{ old('dev_email')}}">
                                                        @if ($errors->has('dev_email'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_email') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Phone <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" name="dev_phone" class="form-control" required value="{{ old('dev_phone')}}">
                                                        @if ($errors->has('dev_phone'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_phone') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Password <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8 password-container">
                                                        <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                                               name="dev_password" maxlength="12" minlength="6" required value="{{ old('dev_password')}}"/>
                                                        <button type="button" id="btnToggle" class="pw-toggle" style="right: 20px;">
                                                            <i id="eyeIcon" class="fa fa-eye"></i>
                                                        </button>
                                                        @if ($errors->has('dev_password'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_password') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Year of birth</label>
                                                    <div class="col-sm-8 input-group date">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="dev_birth_year">
                                                        <div class="w-full">
                                                            @if ($errors->has('dev_birth_year'))
                                                                <ul class="mt-1">
                                                                    @foreach ($errors->get('dev_birth_year') as $error)
                                                                        <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row"><label class="col-sm-4 col-form-label">Student details</label>
                                                    <div class="col-sm-8">
                                                        <div class="border border-edu">
                                                            <textarea rows="3" class="form-control" name="dev_details"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Gender <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b" id="dev_gender" name="dev_gender" >
                                                            <option></option>
                                                            <option {{ old("dev_gender") == 'male' ? "selected":"" }} value="male">Male</option>
                                                            <option {{ old("dev_gender") == 'female' ? "selected":"" }} value="female">Female</option>
                                                            <option {{ old("dev_gender") == 'other' ? "selected":"" }} value="other">Other</option>
                                                        </select>
                                                        @if ($errors->has('dev_gender'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('dev_gender') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Submit status</label>
                                                    <div class="col-sm-8">
                                                        <div class="i-checks">
                                                            <label> <input {{  old('developer_stat') == "enable" ? "checked" : (old('developer_stat') =="disable" ? "" : "checked") }}
                                                                           type="radio" value="enable" name="developer_stat">
                                                                <i></i> Enable </label>
                                                        </div>
                                                        <div class="i-checks">
                                                            <label> <input {{  old('developer_stat') == "disable" ? "checked" : "" }}
                                                                           type="radio" value="disable" name="developer_stat">
                                                                <i></i> Disable </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                {{csrf_field ()}}
                                                <div class="form-group row">
                                                    <div class="col-sm-4 offset-sm-4">
                                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                                        <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                {{-- @endcan --}}

                                {{-- @can(UserManageAbilities::CREATE_MANAGERS) --}}
                                    <div role="tabpanel" id="tab-managers" class="tab-pane">
                                        <div class="panel-body">
                                            @foreach ($errors->all() as $error)
                                                {{-- $error --}}
                                            @endforeach

                                            @if(Session::has('manager_add_message'))
                                                <x-flash-message  
                                                    :class="Session::get('manager_add_cls', 'flash-info')"  
                                                    :title="Session::get('manager_add_msgTitle') ?? 'Info!'" 
                                                    :message="Session::get('manager_add_message') ?? 'Info!'"  
                                                    :message2="Session::get('manager_add_message2') ?? ''"  
                                                    :canClose="true" />
                                            @endif

                                            <form class="" id="" action="" method="post">
                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Name <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="manager_name" class="form-control"  required value="{{ old('manager_name')}}">
                                                        @if ($errors->has('manager_name'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_name') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Username</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="manager_uname" class="form-control" value="{{ old('manager_uname')}}">
                                                        <small>Leave blank if you want to auto generate username</small><br>
                                                        <small>Only aplha numeric charaters allowed (no spaces, no special characters)</small>
                                                        @if (Session::get('is_manager_usernameFill')=='y' && $errors->has('manager_uname'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_uname') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="email" name="manager_email" class="form-control" required value="{{ old('manager_email')}}">
                                                        @if ($errors->has('manager_email'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_email') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Phone <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" name="manager_phone" class="form-control" required value="{{ old('manager_phone')}}">
                                                        @if ($errors->has('manager_phone'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_phone') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Password <span class="text-red-500 text-sm font-bold">*</span></label>

                                                    <div class="col-sm-8 password-container">
                                                        <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                                               name="manager_password" maxlength="12" minlength="6" required value="{{ old('manager_password')}}"/>
                                                        <button type="button" id="btnToggle" class="pw-toggle" style="right: 20px;">
                                                            <i id="eyeIcon" class="fa fa-eye"></i>
                                                        </button>
                                                        @if ($errors->has('manager_password'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_password') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Gender <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b" id="manager_gender" name="manager_gender"  required>
                                                            <option></option>
                                                            <option {{ old("manager_gender") == 'male' ? "selected":"" }} value="male">Male</option>
                                                            <option {{ old("manager_gender") == 'female' ? "selected":"" }} value="female">Female</option>
                                                            <option {{ old("manager_gender") == 'other' ? "selected":"" }} value="other">Other</option>
                                                        </select>
                                                        @if ($errors->has('manager_gender'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('manager_gender') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Submit status</label>
                                                    <div class="col-sm-8">
                                                        <div class="i-checks">
                                                            <label> <input {{  old('manager_stat') == "enable" ? "checked" : (old('manager_stat') =="disable" ? "" : "checked") }}
                                                                           type="radio" checked value="enable" name="manager_stat"> <i></i> Enable </label>
                                                        </div>
                                                        <div class="i-checks">
                                                            <label> <input {{  old('manager_stat') == "disable" ? "checked" : "" }}
                                                                           type="radio" value="disable" name="manager_stat"> <i></i> Disable </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                {{csrf_field ()}}
                                                <div class="form-group row">
                                                    <div class="col-sm-4 offset-sm-4">
                                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                                        <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                {{-- @endcan --}}

                                {{-- @can(UserManageAbilities::CREATE_OWNER) --}}
                                    <div role="tabpanel" id="tab-owners" class="tab-pane">
                                        <div class="panel-body">
                                            @if(Session::has('owner_add_message'))
                                                <x-flash-message  
                                                    :class="Session::get('owner_add_cls', 'flash-info')"  
                                                    :title="Session::get('owner_add_msgTitle') ?? 'Info!'" 
                                                    :message="Session::get('owner_add_message') ?? 'Info!'"  
                                                    :message2="Session::get('owner_add_message2') ?? ''"  
                                                    :canClose="true" />
                                            @endif

                                            <form class="" id="" action="" method="post">
                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Name <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="owner_name" class="form-control" required value="{{ old('owner_name')}}">
                                                        @if ($errors->has('owner_name'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_name') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Username</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="owner_uname" class="form-control" value="{{ old('owner_uname')}}">
                                                        <small>Leave blank if you want to auto generate username</small><br>
                                                        <small>Only aplha numeric charaters allowed (no spaces, no special characters)</small>
                                                        @if (Session::get('is_owner_usernameFill')=='y' && $errors->has('owner_uname'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_uname') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="email" name="owner_email" class="form-control" required value="{{ old('owner_email')}}">
                                                        @if ($errors->has('owner_email'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_email') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Phone <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" name="owner_phone" class="form-control" required value="{{ old('owner_phone')}}">
                                                        @if ($errors->has('owner_phone'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_phone') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label">Password <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8 password-container">
                                                        <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                                               name="owner_password" maxlength="12" minlength="6" required value="{{ old('owner_password')}}"/>
                                                        <button type="button" id="btnToggle" class="pw-toggle" style="right: 20px;">
                                                            <i id="eyeIcon" class="fa fa-eye"></i>
                                                        </button>
                                                        @if ($errors->has('owner_password'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_password') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Gender <span class="text-red-500 text-sm font-bold">*</span></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b" id="owner_gender" name="owner_gender" required>
                                                            <option></option>
                                                            <option {{ old("owner_gender") == 'male' ? "selected":"" }} value="male">Male</option>
                                                            <option {{ old("owner_gender") == 'female' ? "selected":"" }} value="female">Female</option>
                                                            <option {{ old("owner_gender") == 'other' ? "selected":"" }} value="other">Other</option>
                                                        </select>
                                                        @if ($errors->has('owner_gender'))
                                                            <ul class="mt-1">
                                                                @foreach ($errors->get('owner_gender') as $error)
                                                                    <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Submit status</label>
                                                    <div class="col-sm-8">
                                                        <div class="i-checks">
                                                            <label> <input {{  old('owner_stat') == "enable" ? "checked" : (old('owner_stat') =="disable" ? "" : "checked") }}
                                                                           type="radio" checked value="enable" name="owner_stat"> <i></i> Enable </label>
                                                        </div>
                                                        <div class="i-checks">
                                                            <label> <input {{  old('owner_stat') == "disable" ? "checked" : "" }}
                                                                           type="radio" value="disable" name="owner_stat"> <i></i> Disable </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>

                                                {{csrf_field ()}}
                                                <div class="form-group row">
                                                    <div class="col-sm-4 offset-sm-4">
                                                        <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                                        <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                {{-- @endcan --}}
                            </div>
                        {{-- 
                        @else
                            <x-flash-message 
                                class="flash-danger"  
                                title="Permission Denied!" 
                                message="you dont have permissions to crete users"  
                                message2=""  
                                :canClose="false" />
                        @endcanany 
                        --}}

                    </div>
                </div>
            </div>
            
        </div>
    </div>
@stop




@section('script-files')
    <!-- iCheck -->
    <script src="{{asset('js/plugins/iCheck/icheck.min.js')}}"></script>

    <!-- Select2 -->
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>

    <!-- Data picker -->
    <script src="{{asset('js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>

    <!-- SUMMERNOTE -->
    <!-- <script src="../assets/summernote-0.8.18/summernote-lite.js"></script> -->
    <script src="{{asset('plugins/summernote-0.8.18/summernote-bs4.js')}}"></script>


    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-encode.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-validate-size.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-image-exif-orientation.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-image-preview.min.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond-plugin-file-validate-type.js')}}"></script>
    <script src="{{asset('plugins/filepond/js/filepond.min.js')}}"></script>
@stop


@section('javascript')
<script>

    /**/
    (function () {
        // We want to preview images, so we need to register the Image Preview plugin
        FilePond.registerPlugin(

            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews dropped images
            FilePondPluginImagePreview,

            FilePondPluginFileValidateType
        );
        // Select the file input and use create() to turn it into a pond
        const pond = FilePond.create(document.querySelector('.pm_profile_img'));

    })();


    jQuery(document).ready(function ($) {
        let selectedTab = window.location.hash;
        selectedTab = (selectedTab=='')?'#tab-project-managers':selectedTab;
        $('.nav-link[href="' + selectedTab + '"]' ).trigger('click');


        //Prevent default hash behavior on page load
        window.scrollTo(0,0);
    });








    $(document).ready(function(){

        //var elem = document.querySelector('.ccode-stat');
        //var init = new Switchery(elem);

        //$('[name="pm_edu-details"]').summernote();

        $('[name="pm_edu_details"]').summernote({
            //placeholder: 'Hello bootstrap 4',
            tabsize: 2,
            height: 250,
            width: '100%',
            toolbar: [

                ['style', ['style']],
                //['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', [
                    'link',
                    //'picture',
                    //'video',
                    'hr'
                ]
                ],
                ['view', [
                    //'fullscreen',
                    'codeview',
                    'help']
                ]
            ],
        });
        @if(old('pm_edu_details'))
            $('[name="pm_edu_details"]').summernote('code', '{{old('pm_edu_details')}}');
        @endif




        $('[name="dev_details"]').summernote({
            //placeholder: 'Hello bootstrap 4',
            tabsize: 2,
            height: 250,
            width: '100%',
            toolbar: [

                ['style', ['style']],
                //['font', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', [
                    'link',
                    //'picture',
                    //'video',
                    'hr'
                ]
                ],
                ['view', [
                    //'fullscreen',
                    'codeview',
                    'help']
                ]
            ],
        });
        @if(old('dev_details'))
            $('[name="dev_details"]').summernote('code', '{{old('dev_details')}}');
        @endif






        $('[name="dev_birth_year"]').datepicker({
            autoclose: true,
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years",
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('dev_birth_year'))
            $("[name='dev_birth_year']").datepicker("update", '{{old('dev_birth_year')}}');
        @endif


        $('[name="pm_birth_year"]').datepicker({
            autoclose: true,
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years",
            endDate: '+0d',
            startDate: '-99y',
        });
        @if(old('pm_birth_year'))
            $("[name='pm_birth_year']").datepicker("update", '{{old('pm_birth_year')}}');
        @endif






        $("#pm_gender").select2({
            placeholder: "Select PM gender",
            allowClear: true,
            width: '100%'
        });
        $("#dev_gender").select2({
            placeholder: "Select developer gender",
            allowClear: true,
            width: '100%'
        });
        $("#manager_gender").select2({
            placeholder: "Select manager gender",
            allowClear: true,
            width: '100%'
        });
        $("#owner_gender").select2({
            placeholder: "Select owner gender",
            allowClear: true,
            width: '100%'
        });



    });
</script>
@stop
