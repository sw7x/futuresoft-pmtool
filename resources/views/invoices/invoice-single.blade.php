@extends('layouts.master',['title' => 'Invoice single'])
@section('title','Invoice single')

@php
/* Sample values for demonstration if $invoice is not provided */
if (!isset($invoice)) {
    $invoice = (object) [
        'name' => 'Q1 Service Fee - AI Logistics Hub',
        'description' => 'Professional services for initial AI system audit, sensor installation supervision, and baseline model training for the Northern Corridor logistics node.',
        'invoice_type' => 'income',
        'currency' => 'USD',
        'amount' => 12500.50,
        'payment_method' => 'Bank Transfer',
        'transaction_reference' => 'TXN-987654321',
        'invoice_date' => '2024-02-15',
        'due_date' => '2024-03-01',
        'proof_of_payment' => 'https://placehold.co/1280x720/EEE/31343C' // Set to string path if exists
    ];
}
@endphp

@section('css-files')
    <style>
        .section-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
            font-weight: 700;
        }
        .section-title:first-of-type {
            margin-top: 0;
        }
        .display-group {
            margin-bottom: 1.25rem;
        }
        .display-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.25rem;
        }
        .display-value {
            font-size: 1rem;
            font-weight: 500;
        }
        .display-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            min-height: 4rem;
        }
        .badge-type {
            padding: 0.35em 0.8em;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .type-income { background: #dcfce7; color: #15803d; }
        .type-cost { background: #fee2e2; color: #991b1b; }
        
        .footer-action{
            margin-top: 20px;
            padding-bottom: 50px;
            display: flex;
            justify-content: flex-end;
            gap: 5px;
        }



        /* ====Invoice Context==== */
        /* 2x2 Info Grid Styling */
        .info-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .info-badge {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            /* Flex basis for 2x2 grid (minus gap) */
            flex: 1 1 calc(50% - 15px);
            min-width: 250px;
            transition: all 0.2s ease;
        }
        .info-badge:hover {
            background: #ffffff;
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .info-badge i {
            font-size: 26px; /* Bigger Icons */
            margin-right: 18px;
            color: #667eea;
            width: 32px;
            text-align: center;
        }
        .info-badge .info-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #718096;
            display: block;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .info-badge .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
        }
        .text-muted-custom {
            color: #a0aec0;
        }
    </style>
@stop

@section('content')
    <div class="ibox-content m-b-sm border-bottom">
        
        <h2 class="m-0 font-bold text-dark">Invoice Context</h2>
            

        <div class="info-group">
            <!-- Project Detail -->
            <div class="info-badge">
                <i class="fa fa-rocket"></i>
                <div>
                    <span class="info-label">Project</span>
                    <span class="info-value">FutureSoft ERP Update</span>
                </div>
            </div>

            <!-- Client Detail -->
            <div class="info-badge">
                <i class="fa fa-id-card-o"></i>
                <div>
                    <span class="info-label">Client</span>
                    <span class="info-value">Global Solutions Inc.</span>
                </div>
            </div>
            
        </div>
    </div>




    <div class="row">
        <div class="col-lg-12">
                                                     
            <div class="ibox">
                <div class="ibox-content">
                    
                    @if(Session::has('pm_add_message'))
                        <x-flash-message  
                            :class="Session::get('pm_add_cls', 'flash-info')"  
                            :title="Session::get('pm_add_msgTitle') ?? 'Info!'" 
                            :message="Session::get('pm_add_message') ?? 'Info!'"  
                            :message2="Session::get('pm_add_message2') ?? ''"  
                            :canClose="true" />
                    @endif

                    <!-- Basic Information -->
                    <div class="section-title"><i class="fa fa-file-text-o mr-2"></i> Basic Information</div>
                    
                    <div class="display-group">
                        <div class="display-label">Invoice Name</div>
                        <div class="display-value text-2xl font-bold">{{ $invoice->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 display-group">
                            <div class="display-label">Invoice Type</div>
                            <div class="display-value">
                                @if(($invoice->invoice_type ?? '') == 'income')
                                @else
                                @endif                                    

                                <span class="badge-type type-income"><i class="fa fa-arrow-up mr-1 text-xs"></i> Income (Receivable)</span>
                                <span class="badge-type type-cost"><i class="fa fa-arrow-down mr-1 text-xs"></i> Cost (Payable)</span>

                            </div>
                        </div>
                    </div>

                    <div class="display-group mt-3">
                        <div class="display-label">Description</div>
                        <div class="display-value text-slate-600 leading-relaxed border-l-4 border-slate-200 pl-4 py-1 bg-slate-50 rounded-r">
                            {{ $invoice->description ?? 'No description provided.' }}
                        </div>
                    </div>

                    <!-- Financial Details -->
                    <div class="section-title"><i class="fa fa-money mr-2"></i> Financial Details</div>
                    <div class="row">
                        <div class="col-md-6 display-group">
                            <div class="display-label">Amount</div>
                            <div class="display-value text-2xl font-bold text-blue-600">
                                <span class="text-sm font-normal text-slate-400 mr-1">{{ $invoice->currency ?? 'USD' }}</span>
                                {{ number_format($invoice->amount ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-6 display-group">
                            <div class="display-label">Payment Method</div>
                            <div class="display-value"><i class="fa fa-university mr-2 text-slate-400"></i> Bank Transfer</div>
                            <div class="display-value"><i class="fa fa-money mr-2 text-slate-400"></i> Cash</div>
                            <div class="display-value"><i class="fa fa-file-text-o mr-2 text-slate-400"></i> Cheque</div>
                            <div class="display-value"><i class="fa fa-globe mr-2 text-slate-400"></i> Online Payment</div>
                        </div>
                        <div class="col-md-6 display-group">
                            <div class="display-label">Transaction Reference</div>
                            <div class="display-value font-mono text-slate-700 bg-slate-100 px-2 py-1 rounded inline-flex align-items-center text-sm">
                                <i class="fa fa-hashtag mr-2 text-slate-400"></i> {{ $invoice->transaction_reference ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Important Dates -->
                    <div class="section-title"><i class="fa fa-calendar mr-2"></i> Important Dates</div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Invoice Date</div>
                            <div class="display-value font-mono font-bold text-slate-700">
                                <i class="fa fa-calendar-check-o mr-2 text-slate-400"></i> {{ $invoice->invoice_date ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 display-group">
                            <div class="display-label">Due Date</div>
                            <div class="display-value font-mono font-bold text-red-500">
                                <i class="fa fa-clock-o mr-2 text-red-300"></i> {{ $invoice->due_date ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="section-title"><i class="fa fa-paperclip mr-2"></i> Attachments</div>
                    <div class="display-group">
                        <div class="display-label">Proof of Payment</div>
                        <div class="display-value">
                            @if($invoice->proof_of_payment)
                                <div class="mt-2 text-left">
                                    <a href="{{ asset($invoice->proof_of_payment) }}" target="_blank" class="inline-block group">
                                        <div class="relative overflow-hidden rounded-lg border border-slate-200 bg-slate-50 shadow-sm transition-all hover:shadow-md max-w-2xl">
                                            <img src="{{ asset($invoice->proof_of_payment) }}" alt="Proof of Payment" class="w-full h-auto block transition-transform duration-300 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                                <i class="fa fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-slate-500 font-medium flex items-center">
                                            <i class="fa fa-external-link mr-1 text-slate-400"></i> Click to enlarge in new tab
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="text-slate-400 italic text-sm p-4 bg-slate-50 border border-dashed border-slate-200 rounded-lg">
                                    <i class="fa fa-info-circle mr-2"></i> No proof of payment uploaded.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-action">
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm font-semibold px-4 mr-2">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
                <a href="#" class="btn btn-primary btn-sm font-semibold px-4">
                    <i class="fa fa-pencil mr-2"></i> Edit
                </a>
            </div>
            
        </div>
    </div>
@stop

@section('script-files')
    <!-- No form plugins needed for display view -->
@stop

@section('javascript')
<script>
    $(document).ready(function(){
        console.log("Invoice details view loaded successfully.");
    });
</script>
@stop
