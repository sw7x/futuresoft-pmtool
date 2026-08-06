@extends('core-module::layouts.master',['title' => 'Project Timeline'])
@section('title','Project Timeline')

@section('css-files')
    <link rel="stylesheet" href="{{asset('plugins/jquery.timeline-2.1.3/dist/jquery.timeline.min.css')}}">
@stop



@section('page-css')
    <style>
        /* Premium Info Box Styling */
        .proj-info {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1ab394;
            border-radius:0px;
            padding: 20px;
            margin-top: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: none; /* Hidden until data is loaded */
        }
        .proj-info .info-row {
            margin-bottom: 10px;
            display: flex;
            border-bottom: 1px solid #f7fafc;
            padding-bottom: 8px;
        }
        .proj-info .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .proj-info .label-text {
            font-weight: 700;
            color: #718096;
            min-width: 180px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .proj-info .value-text {
            color: #2d3748;
            font-size: 14px;
            font-weight: 500;
        }
        .proj-info .value-text#proj-phase-time-period {
            color: #e53e3e;
            font-weight: 600;
        }
        .proj-info i {
            width: 20px;
            color: #a0aec0;
            margin-right: 8px;
        }

        .proj-info .proj-info-header {
            border-bottom: 1px solid #edf2f7;
            margin-bottom: 20px;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .proj-info .proj-info-header h4 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .proj-info .proj-info-header i {
            color: #1ab394;
            font-size: 18px;
            margin-right: 12px;
            width: auto;
        }

        .proj-info .proj-info-close {
            margin-left: auto;
            background: none;
            border: none;
            color: #a0aec0;
            font-size: 20px;
            cursor: pointer;
            padding: 0 5px;
            line-height: 1;
            transition: color 0.2s;
        }
        .proj-info .proj-info-close:hover {
            color: #e53e3e;
        }


        /* Targeting the base popover class */
        .popover {
            border-radius: 1px;    
            font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        
        /* Specifically targeting the header and body if needed */
        .popover-header {
            /* font-weight: bold;*/
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* display: none;*/
        }

        .popover-body {
            font-size: 12px;
            color: #343A40;
        }

        /* Style for the present time marker line */
        .jqtl-present-time {
            border-left: 3px solid #ff0000 !important; /* Red line */
            opacity: 0.8;
            z-index: 100;
        }

        /* Style for the marker dot/indicator */
        .jqtl-present-time::before {
            /*
            content: '●';
            position: absolute;
            left: -8px;
            top: -10px;
            color: #ff0000;
            font-size: 16px;
            text-shadow: 0 0 5px rgba(255,0,0,0.5);
            */
            display: none;

        }

        /* Style for the marker label */
        .jqtl-present-time::after {
            content: 'Today';
            position: absolute;
            /* 
            left: -20px;
            top: -5px;
            */
            left: 0px;
            top: 0px;
            background: #ff0000;
            color: white;
            padding: 2px 2px;
            border-radius: 0px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
            width: 40px;
            /* display: block; */
            height: 15px;
        }

    </style>
@stop


@section('content')
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

    <div class="ibox-content m-b-sm border-bottom">
        <h2 class="mb-4 font-bold text-muted">Select Project</h2>        
        <div class="row">                  
            <div class="col-lg-4">
                <label for="project-select" class="font-weight-bold mb-0 mr-2">Client:<small>(optional)</small></label>
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
            <div class="col-lg-4">
                <label for="project-select" class="font-weight-bold mb-0 mr-2">Project:</label>
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
            <div class="col-lg-4">              
                <label for="project-select" class="font-weight-bold mb-0 mr-2">Action:</label>
                <div class="d-flex justify-between">
                    <button class="btn btn-info mr-5 w-32" id="timeline_generate">Generate</button>
                    <button class="btn btn-danger w-32" id="timeline_destroy">Destroy</button>
                </div>
            </div>      
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-12 border">
                <h4>Project summary</h4>
                <div class="project-info mt-3">
                    <span>📅 Start Date: Jan 15, 2024</span>
                    <span>📅 End Date: Jun 30, 2024</span>
                    <span>👥 Team Size: 8 members</span>
                    <span>🎯 Status: In Progress</span>
                </div>
            </div>
        </div> 
    </div>

    

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <div class="col-lg-12">                
                    <h2 class="mb-4 font-bold text-muted">Project Timeline</h2>
                    <div class="" id='project-timeline-wrapper'>
                        <div id="project-timeline"></div>
                    </div>

                    <div id="proj-info" class="proj-info">
                        <div class="proj-info-header">
                            <i class="fa fa-info-circle"></i>
                            <h4>Project Details</h4>
                            <button type="button" class="proj-info-close" title="Close">&times;</button>
                        </div>
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-folder-open"></i> Phase : </span>
                            <span class="value-text" id="proj-phase"></span>
                        </div>
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-align-left"></i> Description : </span>
                            <span class="value-text" id="proj-phase-description"></span>
                        </div>
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-align-left"></i> Phase Type : </span>
                            <span class="value-text" id="proj-phase-type">Actual</span>
                        </div>                 
                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-calendar"></i> Time Period : </span>
                            <span class="value-text" id="proj-phase-time-period"></span>
                        </div>

                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-calendar"></i> Progress : </span>
                            <span class="value-text" id="proj-phase-progress">70% Completed</span>
                        </div>

                        <div class="info-row">
                            <span class="label-text"><i class="fa fa-calendar"></i>EST to complete: </span>
                            <span class="value-text" id="proj-phase-progress">20h 50Mmins</span>
                        </div>                    
                    </div>

            </div>
        </div>
    </div>
    
@stop   










@section('script-files')
    <script src="{{asset('plugins/jquery.timeline-2.1.3/dist/jquery.timeline.min.js')}}"></script>
    
    {{-- <script src="{{asset('plugins/jquery.timeline-2.1.3/src/timeline.esdoc.js')}}"></script>--}}    
@stop


@section('javascript')
<script>
    
    // Complete eventData array for jQuery.Timeline 2
    const eventDataArr = [
        {
            id: 1,
            start: "2025-01-20 11:09",
            end: "2025-02-28 20:08",
            row: 1,
            bgColor: "rgb(227, 52, 25)",
            color: "#FFFFFF",
            label: "Quam egetPellentesque amet egetPellentesque ut ultrices egetPellentesque Sed",
            content: "Ut. ut, sapien, tellus erat erat tellus in, aliquet. mauris. eu. est tellus at et sapien, erat dictum erat mauris. ut, et, et, eu. mauris. tellus et ut, et, erat ut. sapien lacus, at et sapien sapien dictum erat ut,."
        },
        {
            id: 2,
            start: "2025-01-25 11:09",
            end: "2025-04-28 20:08",
            row: 2,
            bgColor: "rgb(227, 52, 25)",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "Done - Mauris ligula faucibus mi porttitor risus Mauris sed",
            content: "Non ullamcorper nunc a, libero ullamcorper consectetur nunc nunc Etiam ullamcorper ex. mattis, felis. pharetra. accumsan volutpat felis. pharetra. volutpat ex. ullamcorper mattis, volutpat pharetra. ex. ullamcorper. accumsan dolor, ex. ullamcorper. tempus Etiam ex. volutpat volutpat felis. tempus felis. tempus."
        },
        {
            id: 3,
            start: "2025-01-30 11:09",
            end: "2025-04-30 20:08",
            row: 3,
            bgColor: "#db8f1f",
            color: "#FFFFFF",
            label: "At tempor dictum bibendum mattis lectus mauris lorem",
            content: "Ullamcorper mattis quam. a, nisi hendrerit, amet rhoncus. arcu, amet mattis hendrerit, hendrerit, hendrerit, amet arcu, auctor, quam. amet a, posuere auctor, hendrerit, dictum mattis quam. posuere facilisi. hendrerit, rhoncus. amet auctor, hendrerit, a, dictum placerat rhoncus. auctor, facilisi. quam."
        },
        {
            id: 4,
            start: "2025-02-15 11:09",
            end: "2025-06-30 20:08",
            row: 4,
            bgColor: "#db8f1f",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "Done - Ut sociosqu consectetur elementum conubia arcu variusNullam conubia",
            content: "Commodo Vestibulum sit at elit, posuere imperdiet elit, commodo ullamcorper ipsum sit Class imperdiet massa imperdiet, posuere ipsum massa molestie sit Vestibulum Vestibulum elit, molestie Class egestas massa elit, Phasellus Vestibulum imperdiet elit, sit imperdiet leo molestie commodo ullamcorper posuere."
        },
        {
            id: 5,
            start: "2025-04-05 23:57",
            end: "2025-05-14 12:53",
            row: 5,
            bgColor: "#5fb514",
            color: "#FFFFFF",
            label: "Himenaeos Suspendisse commodo sem himenaeos a Suspendisse nibh",
            content: "Feugiat. eleifend euismod, feugiat feugiat turpis eleifend erat.Aenean feugiat. feugiat. lobortis inceptos erat.Aenean euismod, tellus, vehicula euismod, inceptos bibendum feugiat lobortis erat.Aenean auctor justo. feugiat. vehicula auctor eleifend vehicula feugiat justo. feugiat. bibendum euismod, porta bibendum inceptos feugiat. tellus, eleifend."
        },
        {
            id: 6,
            start: "2025-03-15 21:52",
            end: "2025-05-20 17:58",
            row: 6,
            bgColor: "#5fb514",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "Done - In finibus magna ullamcorper facilisis ut finibus est",
            content: "Cras pulvinar risus. porttitor pretium facilisis. mattis litora pulvinar nibh.Nunc nibh.Nunc facilisis. litora pretium mattis sit Lorem risus. Cras pulvinar porttitor pretium Lorem Lorem Cras mattis Lorem mi. pretium magna, risus. porttitor pulvinar Lorem pulvinar mi. porttitor sit nibh.Nunc pretium."
        },
        {
            id: 7,
            start: "2025-04-28 02:29",
            end: "2026-06-30 07:08",
            row: 7,
            bgColor: "#19a6e3",
            color: "#FFFFFF",
            label: "Justo arcu quis arcu lectus ex nisl ultrices",
            content: "Ut, lobortis tempus dapibus lobortis ut, lobortis adipiscing non bibendum purus ex. bibendum urna mauris bibendum laoreet purus mauris eu bibendum tempus eu lobortis lobortis dapibus mauris facilisi. bibendum facilisi. dapibus eu ex. urna dapibus mauris ut, adipiscing urna adipiscing."
        },
        {
            id: 8,
            start: "2025-04-30 15:20",
            end: "2026-06-15 12:16",
            row: 8,
            bgColor: "#19a6e3",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "Done - Pending - Cursus elit auctor Vestibulum lorem ornare lorem volutpat",
            content: "Taciti sollicitudin torquent torquent facilisi. torquent dapibus, sollicitudin viverra sollicitudin adipiscing Sed torquent erat imperdiet urna, interdum arcu urna, viverra erat adipiscing arcu taciti taciti torquent adipiscing interdum vulputate dapibus, torquent arcu eu. Sed imperdiet Sed Sed torquent taciti urna,."
        },
        {
            id: 9,
            start: "2025-07-01 21:34",
            end: "2025-12-16 19:29",
            row: 9,
            bgColor: "#7419e3",
            color: "#FFFFFF",
            label: "Class maximus nunc pulvinar nunc maximus sagittis Vestibulum",
            content: "Dictum imperdiet dictum Class lobortis facilisis. Class himenaeos. dictum justo ullamcorper. felis. velit felis. velit lobortis In porttitor himenaeos. lobortis Class porta a porttitor imperdiet In velit a In lobortis sapien, In dictum lobortis ullamcorper. dictum a In In Class."
        },
        {
            id: 10,
            start: "2025-07-02 23:46",
            end: "2025-12-28 23:48",
            row: 10,
            bgColor: "#7419e3",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "Done - Dapibus sagittis volutpat Aliquam tincidunt Maecenas Maecenas Phasellus",
            content: "Magna magna orci Suspendisse justo, tortor porttitor orci Morbi magna tortor ullamcorper. Suspendisse Morbi diam ac, Suspendisse tortor facilisis taciti justo, taciti ac, facilisis diam dapibus, tortor facilisis ullamcorper. nunc Morbi diam magna porttitor nunc dapibus, porttitor ullamcorper. dapibus, justo,."
        },
        {
            id: 11,
            start: "2025-12-17 23:06",
            end: "2026-02-25 10:52",
            row: 11,
            bgColor: "##795548",
            color: "#FFFFFF",
            label: "In laoreet nibhNunc diam ut hendrerit in ut",
            content: "Tellus est, ullamcorper urna, scelerisque nec dictum lorem. urna, massa congue sed. scelerisque ullamcorper est, tellus posuere, nec et pretium eu. est, ullamcorper massa ullamcorper pretium tellus posuere, pretium massa posuere, pretium dictum eu. est, posuere, lorem. est, nec et."
        },
        {
            id: 12,
            start: "2026-01-02 10:12",
            end: getLocalDateTime(),
            row: 12,
            bgColor: "##795548",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "72% Complted - Est est ad nibhNunc egestas est at vel",
            content: "Diam mauris turpis arcu, cursus. porta facilisis. rutrum arcu, consequat. cursus. turpis Maecenas cursus. fermentum rutrum cursus. tortor rhoncus. rutrum rhoncus. turpis posuere porta mauris rhoncus. posuere metus consequat cursus. mauris rutrum fermentum porta consequat diam rutrum posuere posuere mauris."
        },
        {
            id: 13,
            start: "2026-02-20 02:49",
            end: "2026-05-22 06:02",
            row: 13,
            bgColor: "#6c757d",
            color: "#FFFFFF",
            label: "Scelerisque lobortis maximus sit est interdum sit Lorem",
            content: "Mauris, laoreet laoreet varius.Nullam litora In vel facilisis In laoreet Nulla in, mauris, mauris, litora facilisis facilisis tortor Nulla lacinia vel In litora mauris, varius.Nullam Nulla varius.Nullam amet, Nulla Nulla amet, amet, vel Aliquam vel lacinia facilisis varius.Nullam Nulla mauris,."
        },
        {
            id: 14,
            start: "2026-03-01 14:11",
            end: getLocalDateTime(),
            row: 14,
            bgColor: "#6c757d",
            color: "#FFFFFF",
            extend: {phase: "actual"},
            label: "10% Complted Libero non eros per conubia eu inceptos libero",
            content: "Urna Etiam Sed hendrerit, Sed Vestibulum Vestibulum urna vestibulum Vestibulum eget, Vestibulum Phasellus hendrerit, posuere euismod, Vestibulum vestibulum aptent vitae nunc. sociosqu per tortor euismod, urna eget, nunc. aptent vitae Etiam tortor vitae aptent eget, vestibulum Etiam Sed euismod, Phasellus."
        }
    ];

    const randomColor = () => {
        return '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
    };


    let timelineConfig  = {
        //eventData:eventDataArr,
        "startDatetime": "2025-01-01",
        "endDatetime": "2026-08-01",
        disableLimitter:true,
        "scale": "month",
        "type": "bar",
        rowHeight:96,
        "rows": "auto",
        rowHeight:40,
        "minGridSize": 100,
        "headline": {
            "display": true,
            //"title": "Project Assignment Timeline",
            "range": true,
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            },
        },
        "footer": {
            "display": true,
            //"content": "© MAGIC METHODS 2026",
            "content": "",
            "range": true,
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            }
        },
        "sidebar": {
            "sticky": true,
            /*
            "list": [
                " Employee 1 Item of 2nd row Item of 2nd row",
                " Item of 2nd row",
                " Item of 3rd row",
                " Item of 4th row",
                " Item of 5th row"
            ]
            */
        },         
        "ruler": {
            "top": {
                "lines": [
                    "year",
                    "month",
                    "day",
                    "weekday"
                ],
                "height": 26,
                "fontSize": 13,
                //"color": "#fff",
                "color": "#777777",
                "background": "#FFFFFF",
                //"background": "#1ab394",
                "locale": "en-US",
                "format": {
                    "timeZone": "Asia/Colombo",
                    "hour12": false,
                    "year": "numeric",
                    "month": "long",
                    "day": "numeric",
                    "weekday": "short"
                }
            },
            "bottom": {
                "lines": [
                    "week",
                            //"year"
                ],
                "color": "#777777",
                "background": "#FFFFFF",
                "locale": "en-US",
                "format": {
                    "timeZone": "Asia/Colombo",
                    "hour12": false,
                    "year": "numeric",
                    "week": "ordinal"
                }
            }
        },
        "rangeAlign": "center",


        "eventMeta": {
            "display": false,
            "scale": "day",
            "locale": "en-US",
            "format": {
                "timeZone": "Asia/Colombo"
            },
            "content": ""
        },

        "reloadCacheKeep": false,
        "zoom": false,
        "debug": true,

    };








    $(document).ready(function(){

        // Initialize tooltips
        //$('[data-toggle="tooltip"]').tooltip();

        



        let page_timelineConfig = timelineConfig; 
        let page_eventDataArr   = eventDataArr;

        let $timelineContainer  = $("#project-timeline-wrapper");
        let timelineId          = "#project-timeline";

        //let increment = 1;
        //let lastId = 19;


        $(document).on('click', '#timeline_generate', function() {
            const shortString = Math.random().toString(36).substring(2, 8); // '2j8hsk'

            page_timelineConfig.sidebar.list = [
                "Phase1 estimated " + shortString,
                "Phase1 actual " + shortString,
                "Phase2 estimated " + shortString,
                "Phase2 actual " + shortString,
                "Phase3 estimated " + shortString,
                "Phase3 actual " + shortString,
                "Phase4 estimated " + shortString,
                "Phase4 actual " + shortString,
                "Phase5 estimated " + shortString,
                "Phase5 actual " + shortString,
                "Phase6 estimated " + shortString,
                "Phase6 actual " + shortString,
                "Phase7 estimated " + shortString,
                "Phase7 actual " + shortString,

            ];

            page_timelineConfig.headline.title = 'Project Assignment Timeline' + shortString;

            /*

            let date = new Date('2025-08-07');

            date.setDate(date.getDate() + (increment*5));
            let startDate = date.toISOString().split('T')[0];

            date.setDate(date.getDate() + (increment*10));
            let endDate = date.toISOString().split('T')[0];

            const newEvent = {
                    id: lastId + increment,
                    start: startDate,
                    end: endDate,
                    row: increment,
                    bgColor: randomColor(),
                    color:randomColor(),
                    label: "project-[" + increment + ']',
                    content: "text-[" + increment + ']'
                };
            
            page_eventDataArr.push(newEvent);
            page_timelineConfig.eventData   = page_eventDataArr;

            increment++;
            lastId++;
            */
            page_timelineConfig.eventData = page_eventDataArr;

            const timelineWidget = $timelineContainer.find(timelineId).Timeline(page_timelineConfig);


            timelineWidget.Timeline('initialized', function(elm,opts,usrdata){
                //$('.jqtl-headline-wrapper').append('<div><a href="/" class="btn btn-secondary btn-sm">&laquo; Home</a></div>');
                    
                $('#project-timeline .jqtl-side-index').find('.jqtl-side-index-item').each(function(i,el) {
                    console.log($(this).html());
                    txt = $(this).html();
                    $(this).html('');
                    $(this).append('<div class="font-semibold truncate" style="padding: 1px 5px;max-width:200px">' + txt + '</div>');
                });

                $('#project-timeline .jqtl-side-index-item').css({
                    //'padding'   : '1px 2px',
                    'font-size' :'12px'
                    // Add more properties if needed
                });
                

                $('#project-timeline .jqtl-ruler-line-item').css({'font-family': 'inherit !important'});
                $('#project-timeline .jqtl-event-label').css({'font-size' :'12px'});


                //add gradient to actual timeline events
                $('#project-timeline .jqtl-event-node[data-phase="actual"]').css({
                    'background-image': 'repeating-linear-gradient(-45deg, rgb(0 0 0 / 50%), rgb(0 0 0 / 65%) 1px, transparent 1px, transparent 25px)'
                });



                //console.log(elm);
                //console.log(opts);
                const $parent = $(elm).parent();
                

                // Keep the plugin's horizontal containment
                // But explicitly control vertical overflow
                $parent.css({
                    'overflow-x': 'hidden',  // Keep this (plugin's intention)
                    'overflow-y': 'hidden',  // Add this to prevent vertical scroll
                    'max-width': '100vw'     // Keep this
                });   
                

                timelineWidget.Timeline('openEvent', function (event, eventNodes){              
                    console.log(`The event node with eventID: ${event.eventId} was clickes.`);
                    console.log(event);
                    console.log(eventNodes);
                    // show "The event node with eventID: 1 was clickes." in console
                    console.log(event.content);
                    console.log(event.label);
                    
                    $('#proj-info #proj-phase').html(event.label);
                    $('#proj-info #proj-phase-time-period').html(event.start + ' - ' + event.end);
                    $('#proj-info #proj-phase-description').html(event.content);
                    $('#proj-info').fadeIn(); // Show the box with a smooth fade
                });    


            });


        });



        $(document).on('click', '#timeline_destroy', function() {
            //const instance = timelineWidget.data('jq.timeline');
            const instance = $timelineContainer.find(timelineId).data('jq.timeline');
            
            if (instance) {
                console.log('Destroying timeline instance...');
                
                // Destroy the timeline
                instance.destroy();
                
                // When jQuery.Timeline initializes, it binds its internal click handlers
                // using event delegation on the document level, like this internally:
                //
                //   $(document).on('click.jq.timeline', '.jqtl-event-node', function() {
                //       let instance = $(this).closest(...).data('jq.timeline')
                //       instance._debug(...)
                //   })
                //
                // The key word is "delegation" — the handler lives on the DOCUMENT,
                // not on the timeline element itself.
                //
                // So when you call instance.destroy() and rebuild the HTML,
                // you destroyed the timeline ELEMENT and its data,
                // but that handler on DOCUMENT is still alive and listening.
                //
                // Next time someone clicks anywhere matching '.jqtl-event-node',
                // that old document handler fires, tries to find the instance via .data(),
                // gets undefined (because you destroyed it), and crashes.
                //
                // $(document).off('.jq.timeline') removes ALL handlers
                // that were namespaced under '.jq.timeline' from the document.
                //
                // The namespace suffix is the key — it lets you surgically remove
                // only the plugin's handlers, without touching any other
                // click handlers you or other plugins attached to the document.
                //
                // Without this line, the ghost handler lives on the document forever,
                // one destroy/regenerate cycle away from crashing.
                $(document).off('.jq.timeline');// ← ADD THIS: remove all delegated plugin events from document




                // Clear the element content
                $timelineContainer.find(timelineId).empty();
                
                
                
                console.log('Timeline destroyed successfully');

                $timelineContainer.removeAttr('style');

                $timelineContainer.html(`
                    <div id="project-timeline"></div>
                `);

                $('#proj-info').hide();



            }   

        });


        $(document).on('click', '#proj-info .proj-info-close', function() {
            $('#proj-info').fadeOut();
        });



        /*
        $(document).on('click', '#project-timeline-wrapper #project-timeline .jqtl-event-node', function(e) {
            // Get the timeline instance
            //const instance = $('#project-timeline').data('jq.timeline');
            const instance = $timelineContainer.find(timelineId).data('jq.timeline');
            
            if (!instance || !instance._isInitialized) {
                console.log('Timeline not ready');
                return;
            }
            
            // Get event data from the element
            const uid = $(this).data('uid');
            const events = instance._loadToCache();
            const event = events.find(evt => evt.uid === uid);
            
            if (event) {
                $('#proj-info #proj-phase').html(event.label);
                $('#proj-info #proj-phase-time-period').html(event.start + ' - ' + event.end);
                $('#proj-info #proj-phase-description').html(event.content);
                $('#proj-info').fadeIn();
            }
        });
        */
        



    }); 



function getLocalDateTime() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')} ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
}

</script>
@stop


