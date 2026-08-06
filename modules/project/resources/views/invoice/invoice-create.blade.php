@extends('core-module::layouts.master',['title' => 'Invoice create'])
@section('title','Invoice create')



@section('css-files')

    <!-- select2 -->
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">

    <!-- bootstrap datapicker -->
    <link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">
    
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

                    
                    
                    <form class="" id="invoice-create-form" action="" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-file-text-o"></i> Basic Information</h3>
                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Invoice Name <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="e.g. Q1 Service Fee - AI Logistics Hub">
                                @if ($errors->has('name'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('name') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter invoice details or items included">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('description') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Invoice Type <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <div class="i-checks">
                                    <label> <input {{  old('invoice_type') == "income" ? "checked" : (old('invoice_type') =="cost" ? "" : "checked") }}
                                                   type="radio" checked value="income" name="invoice_type"> <i></i> Income (Receivable)</label>
                                </div>
                                <div class="i-checks">
                                    <label> <input {{  old('invoice_type') == "cost" ? "checked" : "" }}
                                                   type="radio" value="cost" name="invoice_type"> <i></i> Cost (Payable)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Cost Category(ajax) <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" required name="cost_category">
                                    <option value="" disabled selected>Select Cost Category</option>
                                    <option value="">Employee Cost</option>
                                    <option value="">Infrastructure Costs</option>                                    
                                    <option value="">Third-Party Services</option>                                    
                                    <option value="">Other</option>                                    
                                </select>
                                @if ($errors->has('cost_category'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('cost_category') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>





                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-money"></i> Financial Details</h3>

                        
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Amount <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                
                                <div class="row d-flex align-items-center mb-0">
                                    <div class="col-sm-5">
                                        <select class="form-control select2" name="currency">
                                            <option></option>
                                            <option {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }} value="USD">USD - US Dollar</option>
                                            <option {{ old('currency') == 'EUR' ? 'selected' : '' }} value="EUR">EUR - Euro</option>
                                            <option {{ old('currency') == 'GBP' ? 'selected' : '' }} value="GBP">GBP - British Pound</option>
                                            <option {{ old('currency') == 'JPY' ? 'selected' : '' }} value="JPY">JPY - Japanese Yen</option>
                                            <option {{ old('currency', 'LKR') == 'LKR' ? 'selected' : '' }} value="LKR">LKR - Sri Lankan Rupee</option>
                                            <option {{ old('currency') == 'AUD' ? 'selected' : '' }} value="AUD">AUD - Australian Dollar</option>
                                            <option {{ old('currency') == 'CAD' ? 'selected' : '' }} value="CAD">CAD - Canadian Dollar</option>
                                            <option {{ old('currency') == 'CHF' ? 'selected' : '' }} value="CHF">CHF - Swiss Franc</option>
                                            <option {{ old('currency') == 'CNY' ? 'selected' : '' }} value="CNY">CNY - Chinese Yuan</option>
                                            <option {{ old('currency') == 'HKD' ? 'selected' : '' }} value="HKD">HKD - Hong Kong Dollar</option>
                                            <option {{ old('currency') == 'NZD' ? 'selected' : '' }} value="NZD">NZD - New Zealand Dollar</option>
                                            <option {{ old('currency') == 'SGD' ? 'selected' : '' }} value="SGD">SGD - Singapore Dollar</option>
                                            <option {{ old('currency') == 'INR' ? 'selected' : '' }} value="INR">INR - Indian Rupee</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="number" min="0" step="0.01" name="amount" class="form-control" required value="{{ old('amount') }}" placeholder="0.00">
                                    </div>
                                </div>

                                
                                @if ($errors->has('amount'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('amount') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Payment Method <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" required name="payment_method">
                                    <option value="" disabled selected>Select Payment Method</option>
                                    <option {{ old("payment_method") == 'Bank Transfer' ? "selected":"" }} value="Bank Transfer">Bank Transfer</option>
                                    <option {{ old("payment_method") == 'Cash' ? "selected":"" }} value="Cash">Cash</option>
                                    <option {{ old("payment_method") == 'Cheque' ? "selected":"" }} value="Cheque">Cheque</option>
                                    <option {{ old("payment_method") == 'Online Payment' ? "selected":"" }} value="Online Payment">Online Payment</option>
                                </select>
                                @if ($errors->has('payment_method'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('payment_method') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Transaction Reference</label>
                            <div class="col-sm-8">
                                <input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}" placeholder="e.g. TXN-987654321">
                                @if ($errors->has('transaction_reference'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('transaction_reference') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-calendar"></i> Important Dates</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Invoice Date <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" name="invoice_date" required value="{{ old('invoice_date') }}" placeholder="mm / dd / yyyy">
                                @if ($errors->has('invoice_date'))
                                    <ul class="mt-1 w-full">
                                        @foreach ($errors->get('invoice_date') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Due Date <span class="text-red-500 text-sm font-bold">*</span></label>
                            <div class="col-sm-8 input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" name="due_date" required value="{{ old('due_date') }}" placeholder="mm / dd / yyyy">
                                @if ($errors->has('due_date'))
                                    <ul class="mt-1 w-full">
                                        @foreach ($errors->get('due_date') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <h3 class="mb-3 font-bold text-lg"><i class="fa fa-cloud-upload"></i> Attachments</h3>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Proof of Payment</label>
                            <div class="col-sm-8">
                                <input type="file"
                                       class="filepond-img proof_of_payment"
                                       name="proof_of_payment"
                                       accept="image/webp, image/png, image/jpeg, image/gif"
                                       data-max-file-size="2MB"/>
                                <small class="text-muted">Upload a screenshot or scan of the transaction receipt (Max 2MB)</small>
                                @if ($errors->has('proof_of_payment'))
                                    <ul class="mt-1">
                                        @foreach ($errors->get('proof_of_payment') as $error)
                                            <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 offset-sm-4">
                                <button class="btn btn-primary btn-sm px-4" type="submit">Create</button>
                                <button class="btn btn-danger btn-sm px-4" type="reset">Cancel</button>
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

    <!-- Data picker -->
    <script src="{{asset('js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>
    
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
        const pond = FilePond.create(document.querySelector('.proof_of_payment'));

    })();

    $(document).ready(function(){

        // Initialize Datepickers
        {{-- 
        $('.datepicker').datepicker({
            autoclose: true,
            format: "mm/dd/yyyy",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            todayHighlight: true
        }); 


        @if(old('invoice_date'))
            $("[name='invoice_date']").datepicker("update", '{{old('invoice_date')}}');
        @endif

        @if(old('due_date'))
            $("[name='due_date']").datepicker("update", '{{old('due_date')}}');
        @endif
        --}}

        // Initialize Select2
        $("#type").select2({
            placeholder: "Select Invoice Type",
            allowClear: true,
            width: '100%'
        });

        $('[name="payment_method"]').select2({
            placeholder: "Select Payment Method",
            allowClear: true,
            width: '100%'
        });        

        $('[name="currency"]').select2({
            placeholder: "Select currency",
            allowClear: true,
            width: '100%'
        });

    });
</script>
@stop
