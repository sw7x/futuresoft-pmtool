@extends('layouts.master',['title' => 'Empty'])
@section('title','Compose')




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
                    <h1>Compose</h1>

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


