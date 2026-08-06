@extends('core-module::layouts.master',['title' => 'Advanced settings'])
@section('title','Advanced settings')




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
                    <form method="POST" action="#" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Mail Host</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="mail_host" value="smtp.mailtrap.io">
                                <span class="form-text m-b-none text-muted">Hostname of the SMTP server.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Mail Port</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="mail_port" value="2525">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Mail Encryption</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="mail_encryption">
                                    <option value="tls" selected>TLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Maintenance Mode</label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label>
                                        <input type="checkbox" value="1" name="maintenance_mode"> 
                                        <i></i> Enable Maintenance Mode
                                    </label>
                                </div>
                                <span class="form-text m-b-none text-danger small">Turning this on will prevent all non-admin users from accessing the site.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Session Lifetime (Min)</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="session_lifetime" value="120">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Max File Upload Size (MB)</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="max_file_size" value="10">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Auto-Archive Tasks (Days)</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="archive_days" value="90">
                                <span class="form-text m-b-none text-muted">Number of days after which completed tasks are archived.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 offset-sm-4">
                                <button class="btn btn-primary btn-sm" type="submit">Save Advanced Settings</button>
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
    
@stop


@section('javascript')
<script>
    
</script>
@stop


