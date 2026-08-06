@extends('core-module::layouts.master',['title' => '55Client create'])
@section('title','Client create')


@section('css-files')

    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/plugins/iCheck/custom.css')}}" rel="stylesheet">

    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond-plugin-image-preview.min.css')}}">
    <link rel='stylesheet' href="{{asset('plugins/filepond/css/filepond.min.css')}}">

@stop


@section('content')
    <div class="row" id="">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">
                    
                    
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


                    <form class="pm-create-form" id="" action="" method="post">
                    
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-user"></i> Client Information</h3>

                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Client Name <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Enter client name">
                                 @if ($errors->has('name'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('name') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Company Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="Enter company name">
                                @if ($errors->has('company_name'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('company_name') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <!-- Client Type -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Client Type <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label> <input {{  old('client_type') == "initial" ? "checked" : (old('client_type') =="company" ? "" : "checked") }}
                                                   type="radio" checked value="initial" name="client_type"> <i></i> Initial </label>
                                </div>
                                <div class="i-checks">
                                    <label> <input {{  old('client_type') == "company" ? "checked" : "" }}
                                                   type="radio" value="company" name="client_type"> <i></i> Company </label>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-envelope"></i> Contact & Location</h3>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Email <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8" id="email-container">
                                <div class="input-group mb-2 email-entry">
                                    <input type="email" name="email[]" class="form-control" value="{{ old('email.0') }}" placeholder="client@email.com" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-email" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                @if(old('email'))
                                    @foreach(old('email') as $index => $email)
                                        @if($index > 0)
                                            <div class="input-group mb-2 email-entry">
                                                <input type="email" name="email[]" class="form-control" value="{{ $email }}" placeholder="client@email.com">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-danger remove-email" type="button"><i class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                 @if ($errors->has('email'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('email') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Phone <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8" id="phone-container">
                                <div class="input-group mb-2 phone-entry">
                                    <input type="tel" name="phone[]" class="form-control" value="{{ old('phone.0') }}" placeholder="+94 77 123 4567" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success add-phone" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                @if(old('phone'))
                                    @foreach(old('phone') as $index => $phone)
                                        @if($index > 0)
                                            <div class="input-group mb-2 phone-entry">
                                                <input type="tel" name="phone[]" class="form-control" value="{{ $phone }}" placeholder="+94 77 123 4567">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-danger remove-phone" type="button"><i class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                 @if ($errors->has('phone'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('phone') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter full address">{{ old('address') }}</textarea>
                                <small>Separate lines with commas</small><br>
                            </div>
                        </div>

                         <!-- Country -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Country</label>
                            <div class="col-sm-8">
                                <x-country-dropdown id="country" name="country" cls="__selectpicker form-control m-b" req="required" selectedVal="{{old('country')}}">                                
                                </x-country-dropdown>
                                @if ($errors->has('country'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('country') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif                                  
                            </div>
                        </div>                           


                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-info-circle"></i> Additional Information</h3>

                        <!-- Description -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Comments</label>
                            <div class="col-sm-8">
                                <textarea name="comments" class="form-control" rows="3" placeholder="Enter comments">{{ old('comments') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label> <input {{  old('project_status') == "enable" ? "checked" : (old('project_status') =="disable" ? "" : "checked") }}
                                                   type="radio" checked value="enable" name="project_status"> <i></i> Enable </label>
                                </div>
                                <div class="i-checks">
                                    <label> <input {{  old('project_status') == "disable" ? "checked" : "" }}
                                                   type="radio" value="disable" name="project_status"> <i></i> Disable </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-camera"></i> Profile Picture</h3>

                        <!-- Profile Image -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Profile image</label>
                            <div class="col-sm-8">
                                <input type="file"
                                       class="filepond-img client_img"
                                       name="client_img"
                                       accept="image/webp, image/png, image/jpeg, image/gif"
                                       data-max-file-size="1MB"/>
                                <p>Image size : 500x500</p>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col-sm-4 offset-sm-4">
                                <button class="btn btn-primary btn-sm" type="submit">Save changes</button>
                                <button class="btn btn-danger btn-sm" type="reset">Cancel</button>
                            </div>
                        </div>
                    </form>
                                    

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
        const pond = FilePond.create(document.querySelector('.client_img'));

    })();

    $(document).ready(function(){
        $("#client_type").select2({
            placeholder: "Select client type",
            allowClear: true,
            width: '100%'
        });

        $("#country").select2({
            placeholder: "Select a country",
            allowClear: true,
            width: '100%'
        });   
        
        // Dynamic Phone Numbers
        $(".add-phone").click(function(){
            var phoneHtml = `
            <div class="input-group mb-2 phone-entry">
                <input type="tel" name="phone[]" class="form-control" placeholder="+94 77 123 4567">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-phone" type="button"><i class="fa fa-minus"></i></button>
                </div>
            </div>`;
            $("#phone-container").find(".phone-entry").last().after(phoneHtml);
        });

        $(document).on('click', '.remove-phone', function(){
            $(this).closest('.phone-entry').remove();
        });

        // Dynamic Emails
        $(".add-email").click(function(){
            var emailHtml = `
            <div class="input-group mb-2 email-entry">
                <input type="email" name="email[]" class="form-control" placeholder="client@email.com">
                <div class="input-group-append">
                    <button class="btn btn-outline-danger remove-email" type="button"><i class="fa fa-minus"></i></button>
                </div>
            </div>`;
            $("#email-container").find(".email-entry").last().after(emailHtml);
        });

        $(document).on('click', '.remove-email', function(){
            $(this).closest('.email-entry').remove();
        });
        
    });
</script>
@stop



