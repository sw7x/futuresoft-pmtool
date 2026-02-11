@extends('layouts.master',['title' => 'Empty'])
@section('title','Empty')




@section('css-files')

@stop




@section('page-css')
   
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
                          

                    <div class="nikko-aboy-checkbox-container">                
                        <div class="checkbox">
                            <input type="checkbox" id="html">
                            <label for="html">HTML</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="css">
                            <label for="css"></label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" id="javascript">
                            <label for="javascript">Javascript</label>
                        </div>                
                    </div>

                    <table class="table table-condensed border-2">
                        <tbody>
                            <tr class="">
                                <td>
                                    <?php 
                                    $arr = [
                                        'DDE123','CSS','HTML','JAVA','Javascript',
                                        'HTML','DDE123','CSS','JAVA','Javascript',
                                        'CSS','HTML','JAVA','Javascript','DDE123',
                                        'HTML','JAVA','Javascript','ABC123','FFF56',
                                        'JAVA','ABC123','FFF56','DDE123','CSS'
                                    ];
                                    for ($x = 0; $x < 20; $x+=1): ?>
                                    <span class="custom-checkbox-form-group">
                                        <span class="label label-primary mr-3 mb-2 inline-block pt-1 pb-2 pl-2 pr-4">
                                            <input type="checkbox" id="Javascript<?= $x ?>">
                                            <label for="Javascript<?= $x ?>">
                                                <span><a href="hhh" target="_blank"><?= $arr[$x] ?></a></span>
                                            </label>
                                        </span>
                                    </span>
                                    <?php endfor;  ?>
                                </td>
                            </tr>                                            
                        </tbody>
                    </table>


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


