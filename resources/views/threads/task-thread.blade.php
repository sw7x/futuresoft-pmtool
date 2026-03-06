@extends('layouts.master',['title' => 'Empty'])
@section('title','task-thread')




@section('css-files')
    <link rel="stylesheet" href="{{asset('plugins/summernote-0.8.18/summernote-bs4.css')}}">
    <!-- <link href="css/plugins/summernote/summernote-bs4.css" rel="stylesheet">-->       
@stop




@section('page-css')
    <style>
    .forum-avatar img.rounded-circle{
        height:80px;
        width: 80px; 
    }     

    .forum-post-container .media {
        background-color: #b5b5a80d;
        border: 1px solid #a5afa68f;
        border-radius: 4px;
        padding: 10px 20px 10px 20px;
        margin-bottom: 15px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.03);
    }


    /* Highlight and separate the first post in the thread */
    .forum-post-container > .media:first-of-type {
        background-color: #cafbc94f;
        border: 1px solid #0c700e8f;
        border-radius: 4px;
        padding: 20px 20px 40px 20px;
        margin-bottom: 30px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.03);
        margin-right: -5px;
        margin-left: -5px;
    }
    
    ._forum-post-info {
        position: relative;
    }
    
    .forum-thread-labels {
        position: absolute;
        top: 0;
        right: 0;
        {{-- text-align: right; --}}
    }
    
    .forum-thread-labels .label {
        display: block;
        margin-bottom: 5px;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        color: #fff;
    }
    .forum-thread-labels .label-client {
        background-color: #212529ba;
    }

    .forum-thread-labels .label-project {
        background-color: #1ab394; /* Primary/Greenish color */
    }

    .forum-thread-labels .label-task {
        background-color: #f8ac59; /* Warning/Orange color */
    }
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


                   
                    add quote<br/>
                    add attachement<br/>


            <div class="ibox">
                <div class="ibox-content forum-post-container">
                    <div class="_forum-post-info">
                        <div class="forum-thread-labels">
                            <span class="label label-client ">Client : MOS furniture</span>
                            <span class="label label-project">Project : MOS furniture iss print</span>
                            <span class="label label-task">Task Name : TASK123 - impliment ui changes</span>
                        </div>
                        <h1 class="text-navy">General discussion</h1>
                        <span class="text-muted">
                            <span class="mr-2"><i class="fa fa-user"></i> Heshan Daminda</span>
                            <span class=""><i class="fa fa-clock-o"></i>  Today at 10:58 AM</span>
                        </span>
                        <h3>Announcements</h3>
                        <div class="mt-3 _text-muted">
                            <span class="">Task Progress : In Progress</span>
                        </div>                    
                    </div>
                </div>
            </div>        


            <div class="ibox">
                <div class="ibox-content forum-post-container">
                    
                    <div class="media">
                        <a class="forum-avatar" href="#">
                            <img src="{{asset('images/dummy/thread/a1.jpg')}}" class="rounded-circle" alt="image">
                            <div class="author-info">
                                <div class="text-base font-bold text-black">User</div>
                                <div class="text-xs">March 25.2015</div>
                                <span>11:10 PM</span>
                            </div>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">The standard chunk of Lorem Ipsum</h4>
                            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.
                            <br/><br/>
                            Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                            <br/><br/>
                            - Mike Smith
                            CEO, Zender Inc.

                            <div class="text-right mt-2">
                                <button class="btn btn-white btn-xs"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-quote-left"></i> Quote</button>
                            </div>
                        </div>
                    </div>
                    <div class="media">
                        <a class="forum-avatar" href="#">
                            <img src="{{asset('images/dummy/thread/a2.jpg')}}" class="rounded-circle" alt="image">
                            <div class="author-info">
                                <div class="text-base font-bold text-black">User</div>
                                        <div class="text-xs">March 25.2015</div>
                                        <span>11:10 PM</span>
                            </div>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">There are many variations of passages of Lorem Ipsum available</h4>
                            Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures
                            <br/><br/>
                            - Alex Kunter
                            Designer, Kurtner Company
                            <div class="text-right mt-2">
                                <button class="btn btn-white btn-xs"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-quote-left"></i> Quote</button>
                            </div>
                        </div>
                    </div>
                    <div class="media">
                        <a class="forum-avatar" href="#">
                            <img src="{{asset('images/dummy/thread/a3.jpg')}}" class="rounded-circle" alt="image">
                            <div class="author-info">
                                <div class="text-base font-bold text-black">User</div>
                                        <div class="text-xs">March 25.2015</div>
                                        <span>11:10 PM</span>
                            </div>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">Hampden-Sydney College in Virginia</h4>
                             All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures
                            <br/><br/>
                            - Monica Jackson
                            UX developer
                            <div class="text-right mt-2">
                                <button class="btn btn-white btn-xs"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-quote-left"></i> Quote</button>
                            </div>
                        </div>
                    </div>
                    <div class="media">
                        <a class="forum-avatar" href="#">
                            <img src="{{asset('images/dummy/thread/a4.jpg')}}" class="rounded-circle" alt="image">
                            <div class="author-info">
                                <div class="text-base font-bold text-black">User</div>
                                <div class="text-xs">March 25.2015</div>
                                <span>11:10 PM</span>
                            </div>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">Suffered alteration in some form,</h4>
                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures
                            <br/><br/>
                            - John Ken
                            UX/UI developer

                            <div class="media">
                                <div class="media-body">
                                    <a class="_forum-avatar text-navy text-sm" href="#">User said: <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></a>
                                    
                                    <h4 class="media-heading"> Latin words, combined with a handful of mode</h4>
                                    Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.
                                    <div class="photos">
                                        <a href="http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg" target="_blank"> <img src="{{asset('images/dummy/thread/p1.jpg')}}" class="forum-photo inline" alt="image"></a>
                                        <a href="http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg" target="_blank"> <img src="{{asset('images/dummy/thread/p3.jpg')}}" class="forum-photo inline" alt="image"></a>
                                    </div>

                                    Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                    <br/><br/>
                                    - Adam Smith
                                    CEO
                                </div>
                            </div>
                            
                            <div class="media">                                
                                <div class="media-body">
                                    <a class="_forum-avatar text-navy text-sm" href="#">User ABC said: <i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></a>

                                    <h4 class="media-heading">Virginia, looked up one of the more obscure Latin words</h4>
                                    Distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using
                                    <br/><br/>
                                    Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words
                                    <br/><br/>
                                    - Sandra Jackson
                                    UI developer
                                </div>
                            </div>
                            <div class="text-right mt-2">
                                <button class="btn btn-white btn-xs"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-quote-left"></i> Quote</button>
                            </div>                        
                        </div>
                    </div>

                    <div class="">
                        <div class="_summernote">                            
                            <textarea rows="3" class="form-control" name="reply_text">
                                <h3>Hello Jonathan! </h3>
                                dummy text of the printing and typesetting industry. <strong>Lorem Ipsum has been the industry's</strong> standard dummy text ever since the 1500s,
                                when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic
                                typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with
                                <br/>
                                <br/>
                            </textarea>
                        </div>
                        <div class="text-right tooltip-demo mt-2">
                            <button class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Post reply"><i class="fa fa-reply"></i> Post reply</button>
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

        $('[name="reply_text"]').summernote({
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

        @if(old('reply_text'))
            $('[name="reply_text"]').summernote('code', '{{old('reply_text')}}');
        @endif

    })();
</script>
@stop