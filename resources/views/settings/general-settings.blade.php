@extends('layouts.master',['title' => 'General settings'])
@section('title','General settings')




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
                            <label class="col-sm-4 col-form-label font-bold">App Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="app_name" value="FutureSoft PM Tool">
                                <span class="form-text m-b-none text-muted">The name of your application as it appears to users.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Default Language</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="default_language">
                                    <option value="en" selected>English (UK)</option>
                                    <option value="us">English (US)</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Default Currency</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="default_currency">
                                    <option value="INR" selected>INR (₹) - Indian Rupee</option>
                                    <option value="USD">USD ($) - US Dollar</option>
                                    <option value="EUR">EUR (€) - Euro</option>
                                    <option value="GBP">GBP (£) - British Pound</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Week Starts On</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="week_start">
                                    <option value="Monday" selected>Monday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Timezone</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="timezone">
                                    <option value="Asia/Kolkata" selected>Asia/Kolkata (GMT+05:30)</option>
                                    <option value="UTC">UTC (GMT+00:00)</option>
                                    <option value="America/New_York">America/New_York (GMT-05:00)</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Date Format</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="date_format">
                                    <option value="d/m/Y" selected>DD/MM/YYYY (31/12/2026)</option>
                                    <option value="m/d/Y">MM/DD/YYYY (12/31/2026)</option>
                                    <option value="Y-m-d">YYYY-MM-DD (2026-12-31)</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Company Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="company_name" value="FutureSoft Solutions">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Company Address</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="company_address" rows="3">Sector 18, Gurgaon, Haryana, India</textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Admin Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="admin_email" value="admin@futuresoft.com">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Registration</label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label>
                                        <input type="checkbox" value="1" name="allow_registration" checked> 
                                        <i></i> Allow new users to register
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-bold">Footer Copyright Text</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="footer_text" value="&copy; 2026 FutureSoft Solutions Pvt. Ltd.">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 offset-sm-4">
                                <button class="btn btn-primary btn-sm" type="submit">Save settings</button>
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


