<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="{{ config('app.locale') }}"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="{{ config('app.locale') }}"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Morfix') }}</title>

        <meta name="description" content="Morfix - The Best Instagram Growth Hacking Tool">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="assets/img/favicons/favicon.png">

        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-192x192.png" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <!-- Page JS Plugins CSS -->
        @yield('css')

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/app.css') }}">
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!--<link rel="stylesheet" id="css-theme" href="{{ asset('assets/css/themes/modern.min.css') }}">--> 
        <!-- END Stylesheets -->

        <!-- Scripts -->
        <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
        <script>
window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
]) !!}
;
        </script>
    </head>
    <body>
        <div id="page-loader"></div>
        <div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
            @include('side_overlay')

            @yield('sidebar')

            @include('header')

            <!-- Main Container -->
            @yield('content')
            <!-- END Main Container -->

            @include('footer')

        </div>
        <!-- END Page Container -->

        <!-- Apps Modal -->
        <!-- Opens from the button in the header -->
        <div class="modal fade" id="apps-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-sm modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <!-- Apps Block -->
                    <div class="block block-themed block-transparent">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Apps</h3>
                        </div>
                        <div class="block-content">
                            <div class="row text-center">
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="base_pages_dashboard.html">
                                        <div class="block-content text-white bg-default">
                                            <i class="si si-speedometer fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Backend</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="frontend_home.html">
                                        <div class="block-content text-white bg-modern">
                                            <i class="si si-rocket fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Frontend</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Apps Block -->
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->

        <!-- Apps Modal -->
        <!-- Opens from the button in the header -->
        <div class="modal fade" id="upgrade-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-lg modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <!-- Apps Block -->
                    <div class="block block-themed block-transparent">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Upgrade your account now!</h3>
                        </div>
                        <div class="block-content">
                            <div class="row text-center">

                                <div class="col-sm-6 col-lg-4 visibility-hidden" data-toggle="appear" data-offset="50" data-class="animated fadeInDown">
                                    <!-- FREE Plan -->
                                    <div class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                                        <div class="block-header" style='background-color: #f5f5f5; padding-top: 10px; 
                                             padding-left: 10px; padding-right: 10px; padding-bottom: 5px;
                                             border-bottom: none;'>
                                            <div style='padding:5px; background-color:rgb(30, 115, 190); height: 274px;'>
                                                <h3 class="push-20-t block-title h1 font-w700 text-white" 
                                                    style="font-family: 'Montserrat', sans-serif; font-size: 25px;">PREMIUM</h3>
                                                <div class="push-10-t block-title h6 text-white" 
                                                     style="font-size: 14px; text-transform: none; font-weight: 100;
                                                     font-style: italic; font-family: 'Lora', serif;">&nbsp;</div>
                                                <div class="push-20-t text-white" 
                                                     style="font-family: 'Montserrat', sans-serif; 
                                                     font-style: normal; font-weight: normal; height: 200px;">
                                                    <span style="font-size: 30px; top: -20px; position:relative;">$</span>
                                                    <span style="font-size: 60px; top: -20px; padding-left: 10px;">37/mth</span>
                                                    <div class="push-30-t block-title h6 text-white" 
                                                         style="font-size: 14px; text-transform: none; font-weight: 100;
                                                         font-style: italic; font-family: 'Lora', serif;">Free Trial Available</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content" style='background-color: #f5f5f5; padding-top: 0px;
                                             padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>
                                            <div style='background-color:#f5f5f5;'>
                                                <div style="font-family: 'Raleway'; padding-top: 35px; font-weight: 400; 
                                                     padding-bottom: 20px; padding-left: 30px; padding-right: 30px;">
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        No Morfix.co branding
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Full Speed
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        High Priority Support
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Instagram Affiliate Training
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Auto Interaction (Like, Comment Follow, Unfollow)
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Greet New Followers With Direct Message
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Unlimited Scheduled Posts
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        First Comment Function
                                                    </p>
                                                    <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                        Private Facebook Group
                                                    </p>
                                                </div>
                                                <div style='padding-top: 0px; padding-bottom: 40px;'>
                                                    <a href='/app/register.php' class="text-white" style='
                                                       background-color:rgb(30, 115, 190); 
                                                       padding: 10px 50px;
                                                       font-weight: 600; 
                                                       font-size: 15px; 
                                                       border: solid 1px #D3D3D3;
                                                       text-align: center;
                                                       text-transform: uppercase;'>
                                                        Try Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END FREE Plan -->
                                </div>

                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="frontend_home.html">
                                        <div class="block-content text-white bg-modern">
                                            <i class="si si-rocket fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Frontend</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Apps Block -->
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->


        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.scrollLock.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.appear.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.countTo.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.placeholder.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/js.cookie.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>

        @yield('js')
    </body>

</html>