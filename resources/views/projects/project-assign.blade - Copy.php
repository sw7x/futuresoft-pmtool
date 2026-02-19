@extends('layouts.master',['title' => 'Empty'])
@section('title','project-assign')




@section('css-files')
    
@stop




@section('page-css')
    <style>
        
    </style>
@stop


@section('content')
    

    <div class="">
        
        <div class="row">
            <div class="col-lg-12">
                @if(Session::has('message'))
                    <x-flash-message  
                        :class="Session::get('cls', 'flash-info')"  
                        :title="Session::get('msgTitle') ?? 'Info!'" 
                        :message="Session::get('message') ?? ''"  
                        :message2="Session::get('message2') ?? ''"  
                        :canClose="true" />
                @endif
            </div>
        </div>


        <div class="row">                   

            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title d-flex justify-content-between align-items-center pr-4">
                        <h5 class="m-0">Available Developers</h5>
                        <span class="border rounded-sm _label _label-default px-2 py-1 text-xs bg-gray-200">8 Developers</span>
                    </div>


                    <div class="ibox-content">
                        <p  class="m-b-lg">
                            <strong>Nestable</strong> is an interactive hierarchical list. You can drag and drop to rearrange the order. It works well on touch-screens.
                        </p>


            <div class="m-b-md">
                <label for="project-select" class="font-weight-bold mb-1 mr-2">Select Developer:</label>
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










                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="1">
                                    <div class="dd-handle">1 - Lorem ipsum</div>
                                </li>
                                <li class="dd-item" data-id="2">
                                    <div class="dd-handle">2 - Dolor sit</div>                                    
                                </li>
                                <li class="dd-item" data-id="5">
                                    <div class="dd-handle">5 - Consectetuer</div>                                    
                                </li>
                                <li class="dd-item" data-id="8">
                                    <div class="dd-handle">8 - Tation ullamcorper</div>
                                </li>
                                <li class="dd-item" data-id="9">
                                    <div class="dd-handle">9 - Ea commodo</div>
                                </li>
                            </ol>
                        </div>
                        <div class="m-t-md">
                            <h5>Serialised Output</h5>
                        </div>
                        <textarea id="nestable-output" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title d-flex justify-content-between align-items-center pr-4">
                        <h5 class="m-0">Assigned Developers</h5>
                        <span class="border rounded-sm _label _label-default px-2 py-1 text-xs bg-gray-200">5 Developers</span>
                    </div>
                    <div class="ibox-content">
                        <x-flash-message  
                            class="flash-info"
                            title=""
                            message2="Drag developers from the available list to assign them to this project"  
                            :canClose="false" />

                        <x-flash-message  
                            class="flash-warning"
                            title=""
                            message2=" ⚠️ You have unsaved changes. Click 'Confirm Assignments' to save. "  
                            :canClose="true" />

                        <div class="dd" id="nestable2">
                            <ol class="dd-list">
                                <li class="dd-item" data-id="10">
                                    <div class="dd-handle">
                                        <span class="label label-info"><i class="fa fa-users"></i></span> Cras ornare tristique.
                                    </div>                                    
                                </li>

                                <li class="dd-item" data-id="50">
                                    <div class="dd-handle">
                                        <span class="label label-warning"><i class="fa fa-users"></i></span> Integer vitae libero.
                                    </div>                                    
                                </li>
                            </ol>
                        </div>

                        <div class="d-flex mt-4">
                            <button type="button" id="" class="update-designation-info btn btn-primary flex-fill mr-2 w-100 shadow-sm font-semibold">
                                <i class="fa fa-check mr-1"></i> Confirm
                            </button>
                            <button type="button" class="reset btn btn-danger flex-fill ml-2 w-100 shadow-sm font-semibold">
                                <i class="fa fa-times mr-1"></i> Cancel
                            </button>
                        </div>






                        <div class="m-t-md">
                            <h5>Serialised Output</h5>
                        </div>

                        <textarea id="nestable2-output" class="form-control"></textarea>
                    </div>
                </div>
            </div>                

        </div>
    </div>






    





@stop




@section('script-files')
    <!-- Nestable List -->
    <script src="{{asset('js/plugins/nestable/jquery.nestable.js')}}"></script>    
@stop


@section('javascript')
<script>
    $(document).ready(function(){

        var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                output = list.data('output');
                
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };
            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            }).on('change', updateOutput);

            // activate Nestable for list 2
            $('#nestable2').nestable({
               group: 1
            }).on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));
            updateOutput($('#nestable2').data('output', $('#nestable2-output')));

            
       });    
</script>
@stop


