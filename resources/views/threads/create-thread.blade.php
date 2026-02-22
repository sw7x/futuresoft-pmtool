@extends('layouts.master',['title' => 'Empty'])
@section('title','Empty')




@section('css-files')
    <link rel="stylesheet" href="{{asset('plugins/summernote-0.8.18/summernote-bs4.css')}}">
    <!-- <link href="css/plugins/summernote/summernote-bs4.css" rel="stylesheet">-->       
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                   

            <div class="col-lg-6">                
                <label for="project-select" class="font-weight-bold mb-0 mr-2">Select Client:<small>(optional)</small></label>
                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                    <option></option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                </select>
            </div>

            <div class="col-lg-6">                
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Project:</label>
                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                    <option></option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                </select>
            </div>                                               

        </div>
    </div>

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">                   

            <div class="col-lg-6">                
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Task:</label>
                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                    <option></option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                </select>
            </div>

            <div class="col-lg-6">                
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Thread Type:</label>
                <select class="select-project form-control select2" style="width: 100%;" data-placeholder="Select a Project">
                    <option></option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                </select>
            </div>                                               

        </div>
    </div>

    


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
                <div class="ibox-content forum-post-container">
                    <div class="">
                        <div class="_summernote">                            
                            <textarea rows="3" class="form-control" name="thread_text">
                                <h3>Hello Jonathan! </h3>
                                dummy text of the printing and typesetting industry. <strong>Lorem Ipsum has been the industry's</strong> standard dummy text ever since the 1500s,
                                when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic
                                typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with
                                <br/>
                                <br/>
                            </textarea>
                        </div>
                        <div class="text-right tooltip-demo mt-2">
                            <button class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Post thread"><i class="fa fa-reply"></i> Post thread</button>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
@stop




@section('script-files')
    <!-- SUMMERNOTE -->
    <!-- <script src="../assets/summernote-0.8.18/summernote-lite.js"></script> -->
    <script src="{{asset('plugins/summernote-0.8.18/summernote-bs4.js')}}"></script>    
@stop


@section('javascript')
<script>
    (function () {

        $('[name="thread_text"]').summernote({
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

        @if(old('thread_text'))
            $('[name="thread_text"]').summernote('code', '{{old('thread_text')}}');
        @endif

    })();
</script>
@stop


