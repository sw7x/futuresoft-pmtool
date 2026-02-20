<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>
        <!-- META SECTION -->
        <title>ADMIN Login | FutureSoft</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon" />
        <!-- END META SECTION -->


        <!-- CSS INCLUDE -->
        <link rel="stylesheet" type="text/css" id="theme" href="{{asset('css/login/theme-default-head-light.css')}}"/>
        <!-- EOF CSS INCLUDE -->

        <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css'>

        <style>
            /* ------------------------------------ */
            /* Show/Hide password field ------------*/
            /* ------------------------------------ */
            .password-container{
                position: relative;
            }
            .password-container .pw-toggle{
                background: none;
                border: none;
                color: #337ab7;
                /* display: none; */
                font-size: 1.3em;
                font-weight: 600;
                /* padding: .5em; */
                position: absolute;
                right: 7px;
                top: 7px;
                z-index: 9;
            }

            /* ------------------------------------ */
            /* custom flash messages ------------*/
            /* ------------------------------------ */
            .flash-msg {
                padding: 15px;
                margin-bottom: 20px;
                border: 1px solid transparent;
                border-radius: 4px;
            }
            .flash-success {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            .flash-info {
                color: #31708f;
                background-color: #d9edf7;
                border-color: #bce8f1;
            }
            .flash-warning {
                color: #8a6d3b;
                background-color: #fcf8e3;
                border-color: #faebcc;
            }
            .flash-danger {
                color: #a94442;
                background-color: #f2dede;
                border-color: #ebccd1;
            }


            /* ------------------------------------ */
            /* custom checkbox   -------------------*/
            /* ------------------------------------ */
            .custom-checkbox {
                display: block;
                position: relative;
                padding-left: 30px;
                margin-bottom: 12px;
                cursor: pointer;
                font-size: 11px;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                color: #fff;
                font-weight: 200;
            }

            .custom-checkbox input {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                height: 0;
                width: 0;
            }

            .checkmark {
                position: absolute;
                top: 0;
                left: 0;
                height: 15px;
                width: 15px;
                background-color: #eee;
                border-radius: 2px;
            }

            .custom-checkbox:hover input ~ .checkmark {
                background-color: #ccc;
            }

            .custom-checkbox input:checked ~ .checkmark {
                background-color: #39424c; /* Login button color */
            }

            .checkmark:after {
                content: "";
                position: absolute;
                display: none;
            }

            .custom-checkbox input:checked ~ .checkmark:after {
                display: block;
            }

            .custom-checkbox .checkmark:after {
                left: 5px;
                top: 1px;
                width: 7px;
                height: 12px;
                border: solid white;
                border-width: 0 3px 3px 0;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
            }

        </style>
    </head>
    <body>

        <div class="login-container">

            <div class="login-box animated fadeInDown">
                <div class="login-logo1"></div>
                <div class="login-body">
                    <div class="login-title">LOGIN</div>


                    @if(Session::has('message'))
                        <x-flash-message  
                            :class="Session::get('cls', 'flash-info')"  
                            :title="Session::get('msgTitle') ?? 'Info!'" 
                            :message="Session::get('message') ?? ''"  
                            :message2="Session::get('message2') ?? ''"  
                            :canClose="true" />
                    @endif



                    <form action="" class="form-horizontal" method="post" autocomplete="off">

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="Username or Email *" name="uname" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="password-container">
                                    <input type="password" class="password_field form-control" placeholder="Password (6 to 12 alpha numeric characters) *"
                                           name="password" maxlength="12" minlength="6" required/>
                                    <button type="button" id="btnToggle" class="pw-toggle">
                                        <i id="eyeIcon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="custom-checkbox">Remember Me
                                    <input type="checkbox" name="remember_me">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" name="admin_submit">Log In</button>
                            </div>
                            <div class="col-md-6">
                                <button type="reset" class="btn btn-warning btn-block" name="admin_submit">Reset</button>
                            </div>
                        </div>

                        <div class="form-group" id="" style="margin-top:15px;">
                            <div class="col-md-6" style="color:#fff;">
                                <span style="color:red;">*</span> - Required Fields
                            </div>
                        </div>

                        {{ csrf_field() }}
                    </form>

                </div>

            </div>

        </div>

        <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
        <script>
            // Show/Hide password field
            $('.pw-toggle').click(function(event){
                let icon          = $(this).children('i');
                let passwordInput = $(this).parent().find('input.password_field');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.prop('type', 'text');
                    icon.addClass("fa-eye-slash");
                } else {
                    passwordInput.prop('type', 'password');
                    icon.removeClass("fa-eye-slash");
                }
                //icon.addClass('aa');
                //passwordInput.addClass('bb');
            });


			$('.flash-msg .close').click(function(event){
				$(this).parent().fadeOut(700, function(){ $(this).remove();});
			});
        </script>
    </body>
</html>
