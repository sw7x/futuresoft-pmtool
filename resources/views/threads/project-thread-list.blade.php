@extends('layouts.master',['title' => 'Project Thread List'])
@section('title','project-thread-list')




@section('css-files')
    
@stop




@section('page-css')
    <style>
    .forum-icon {
        width: 55px;
        margin-right: 10px;
    }

    float: left;    
    </style>
@stop


@section('content')
    
    @if(Session::has('message'))
        <x-flash-message  
        :class="Session::get('cls', 'flash-info')"  
        :title="Session::get('msgTitle') ?? 'Info!'" 
        :message="Session::get('message') ?? ''"  
        :message2="Session::get('message2') ?? ''"  
        :canClose="true" />
    @endif
    
    
    


    <div class="row" id="_sortable-view">
        <div class="col-lg-12">
        
            <div class="ibox">
                <div class="ibox-content forum-container">                        
                    <div class="forum-title mb-4" >
                        <h2 class="text-center">Project Name</h2>
                    </div>




                        <div class="forum-title">
                            <div class="float-right forum-desc">
                                <samll>Total posts: 320,800</samll>
                            </div>
                            <h3 class="">Project Thread Category</h3>
                        </div>

                        

                        <div class="forum-item active">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="forum-item">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="forum-item">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>



                        

                        <div class="forum-title">
                            <div class="float-right forum-desc">
                                <samll>Total posts: 17,800,600</samll>
                            </div>
                            <h3>Project Thread Category 2</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                   





                </div>
            </div>


            <div class="ibox">
                <div class="ibox-content forum-container">                        
                    <div class="forum-title mb-4" >
                        <h2 class="text-center">Project Name</h2>
                    </div>




                        <div class="forum-title">
                            <div class="float-right forum-desc">
                                <samll>Total posts: 320,800</samll>
                            </div>
                            <h3 class="">Project Thread Category</h3>
                        </div>

                        

                        <div class="forum-item active">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="forum-item">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                        <div class="forum-item">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>



                        

                        <div class="forum-title">
                            <div class="float-right forum-desc">
                                <samll>Total posts: 17,800,600</samll>
                            </div>
                            <h3>Project Thread Category 2</h3>
                        </div>

                        <div class="forum-item active">
                            <div class="row">                         
                                <div class="col-md-9">
                                    <div class="forum-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <a href="forum_post.html" class="forum-item-title">1General Discussion</a>
                                    <div class="forum-sub-title">Talk about sports, entertainment, music, movies, your talk about enything.</div>
                                    <div class="text-muted text-xs mt-1 ml-16">
                                        <span class="font-weight-bold">Posted by -</span>
                                        <span>user1</span>
                                        <span class="mx-1">:</span>
                                        <span>2025 February 12</span>
                                    </div>
                                </div>                       
                                <div class="col-md-1 forum-info border-l border-dotted border-gray-300">
                                    <span class="views-number">
                                        1216
                                    </span>
                                    <div>
                                        <small>Replies</small>
                                    </div>
                                </div>                                
                                <div class="col-md-2 forum-info border-l border-dotted border-gray-300">                                    
                                    <span class="views-number">User1</span>
                                    <div><small>Last Updated</small></div>
                                    <div><small>2 hours 35 minutes ago</small></div>
                                </div>
                            </div>
                        </div>

                   





                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Project threads pagination" class="my-3">
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        
        </div>
    </div>
@stop




@section('script-files')
    
@stop


@section('javascript')
<script>
    
</script>
@stop


