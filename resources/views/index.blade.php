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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,200,300,400,400italic,600,700%7COpen+Sans:100,200,300,400,400italic,600,700">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,400i|Montserrat:400,900|Raleway">

        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="assets/js/plugins/slick/slick.min.css">
        <link rel="stylesheet" href="assets/js/plugins/slick/slick-theme.min.css">

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" id="css-main" href="assets/css/oneui.css">
        <link rel="stylesheet" id="css-main" href="assets/css/app.css">
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>
    </head>
    <body>
        <!-- Page Container -->
        <!--
            Available Classes:

            'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

            'sidebar-l'                  Left Sidebar and right Side Overlay
            'sidebar-r'                  Right Sidebar and left Side Overlay
            'sidebar-mini'               Mini hoverable Sidebar (> 991px)
            'sidebar-o'                  Visible Sidebar by default (> 991px)
            'sidebar-o-xs'               Visible Sidebar by default (< 992px)

            'side-overlay-hover'         Hoverable Side Overlay (> 991px)
            'side-overlay-o'             Visible Side Overlay by default (> 991px)

            'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

            'header-navbar-fixed'        Enables fixed header
            'header-navbar-transparent'  Enables a transparent header (if also fixed, it will get a solid dark background color on scrolling)
        -->
        <div id="page-container" class="side-scroll header-navbar-fixed header-navbar-transparent">

            <!-- Header -->
            <header id="header-navbar" class="content-mini content-mini-full">
                <div class="content-boxed">
                    <!-- Header Navigation Right -->
                    <!--                    <ul class="nav-header pull-right">
                                            <li>
                                                 Themes functionality initialized in App() -> uiHandleTheme() 
                                                <div class="btn-group">
                                                    <button class="btn btn-link text-white dropdown-toggle" data-toggle="dropdown" type="button">
                                                        <i class="si si-drop"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right sidebar-mini-hide font-s13">
                                                        <li>
                                                            <a data-toggle="theme" data-theme="default" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-default pull-right"></i> <span class="font-w600">Default</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-amethyst pull-right"></i> <span class="font-w600">Amethyst</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="theme" data-theme="assets/css/themes/city.min.css" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-city pull-right"></i> <span class="font-w600">City</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="theme" data-theme="assets/css/themes/flat.min.css" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-flat pull-right"></i> <span class="font-w600">Flat</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="theme" data-theme="assets/css/themes/modern.min.css" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-modern pull-right"></i> <span class="font-w600">Modern</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" tabindex="-1" href="javascript:void(0)">
                                                                <i class="fa fa-circle text-smooth pull-right"></i> <span class="font-w600">Smooth</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="hidden-md hidden-lg">
                                                 Toggle class helper (for main header navigation below in small screens), functionality initialized in App() -> uiToggleClass() 
                                                <button class="btn btn-link text-white pull-right" data-toggle="class-toggle" data-target=".js-nav-main-header" data-class="nav-main-header-o" type="button">
                                                    <i class="fa fa-navicon"></i>
                                                </button>
                                            </li>
                                        </ul>-->
                    <!-- END Header Navigation Right -->

                    <!-- Main Header Navigation -->
                    <ul class="js-nav-main-header nav-main-header pull-right">
                        <li class="text-right hidden-md hidden-lg">
                            <!-- Toggle class helper (for main header navigation in small screens), functionality initialized in App() -> uiToggleClass() -->
                            <button class="btn btn-link text-white" data-toggle="class-toggle" data-target=".js-nav-main-header" data-class="nav-main-header-o" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </li>

                        <li>
                            <a class="active" href="frontend_home_header_nav.html">Home</a>
                        </li>

                        <!--                        <li>
                                                    <a class="nav-submenu" href="javascript:void(0)">Pages</a>
                                                    <ul>
                                                        <li>
                                                            <a href="frontend_team.html">Team</a>
                                                        </li>
                                                        <li>
                                                            <a href="frontend_support.html">Support</a>
                                                        </li>
                                                        <li>
                                                            <a href="frontend_search.html">Search</a>
                                                        </li>
                                                        <li>
                                                            <a href="frontend_about.html">About</a>
                                                        </li>
                                                        <li>
                                                            <a href="frontend_login.html">Login</a>
                                                        </li>
                                                        <li>
                                                            <a href="frontend_signup.html">Sign Up</a>
                                                        </li>
                                                    </ul>
                                                </li>-->

                        <li>
                            <a href="/blog">Blog</a>
                        </li>
                        <li>
                            <a href="/login">Login</a>
                        </li>
                        <li>
                            <a href="/register">Free Trial</a>
                        </li>
                    </ul>
                    <!-- END Main Header Navigation -->

                    <!-- Header Navigation Left -->
                    <ul class="nav-header pull-left">
                        <li class="header-content">
                            <a class="h5" href="/">
                                <img style="height: 37px;" src="assets/img/logo/morfix-dark-logo-3.png" alt="Morfix Logo"/>
                            </a>
                        </li>
                    </ul>
                    <!-- END Header Navigation Left -->

                </div>
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Hero Content -->
                <div class="bg-video" data-vide-bg="assets/img/videos/hero_tech" data-vide-options="posterType: jpg, position: 50% 75%">
                    <div style='background-color: rgba(0, 0, 0, 0.53);'>
                        <section class="content content-full content-boxed">
                            <!-- Section Content -->
                            <div class="text-center push-50-t push-50 visibility-hidden" data-toggle="appear" data-class="animated fadeIn">
                                <a class="fa-2x text-white">&nbsp;</a>
                            </div>

                            <div class="push-10-t push-50 text-center">
                                <h1 class="h1 font-w700 text-white push-10 animated fadeInDown" data-toggle="appear" data-class="animated fadeInDown">Hi, my name is Morfix.</h1>
                            </div>

                            <div class="push-20-t push-50 text-center">
                                <h1 class="h2 font-s64 font-w700 text-white push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">I can automate your Instagram</h1>
                                <h2 class="h3 font-s64 font-w700 text-white-op visibility-hidden" data-toggle="appear" data-timeout="750" data-class="animated fadeIn">
                                    <em id='function-typed'>Followers Growth</em>
                                </h2>
                            </div>

                            <div class="push-10-t push-50 text-center">
                                <h2 class="h5 font-w700 text-white-op animated fadeInDown" data-toggle="appear" data-class="animated fadeInDown">
                                    Free Trial. No Credit Card Required.</h2>
                                <div class="push-50-t push-20 text-center">
                                    <a class="btn btn-noborder btn-lg btn-primary push-5 visibility-hidden" 
                                       style="background-color:#c41a00; font-size: 15px;"
                                       data-toggle="appear" 
                                       data-class="animated fadeInRight" href="base_pages_dashboard.html">
                                        Start My Trial Now
                                    </a>
                                </div>
                            </div>
                            <!-- END Section Content -->
                        </section>
                    </div>
                </div>
                <!-- END Hero Content -->

                <!-- Ratings -->
                <div class="bg-image">
                    <div style='background-color: rgba(0, 0, 0, 0.83);'>
                        <section class="content content-full content-boxed" style='max-width: 100%;'>
                            <!-- Section Content -->
                            <div class="push-10-t push-50 text-center">
                                <h1 class="h3 font-w400 text-white-op push-10 animated fadeInDown" data-toggle="appear" data-class="animated fadeInDown">
                                    Join Over 1,700 users Automating their Instagram Exposure</h1>
                            </div>
                            <div class="row items-push-2x push-50-t text-center">
                                <div class="col-sm-3 visibility-hidden" data-toggle="appear" data-offset="-150">
                                    <img src="assets/img/logo/keystodoors-logo.png" alt="Keys to Doors Logo">
                                    <!--                                    <div class="text-warning push-10-t push-15">
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                        </div>
                                                                        <div class="h4 text-white-op push-5">Professional design in a reliable UI framework! A pure joy to work with!</div>
                                                                        <div class="h6 text-gray">- Ann Parker</div>-->
                                </div>

                                <div class="col-sm-3 visibility-hidden" data-toggle="appear" data-offset="-150" data-timeout="150">
                                    <img src="assets/img/logo/lifestylemafia-logo.png" alt="Keys to Doors Logo">
                                    <!--                                    <div class="text-warning push-10-t push-15">
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                        </div>
                                                                        <div class="h4 text-white-op push-5">Awesome support! Our Web Application looks and works great!</div>
                                                                        <div class="h6 text-gray">- Scott Ruiz</div>-->
                                </div>
                                <div class="col-sm-3 visibility-hidden" data-toggle="appear" data-offset="-150" data-timeout="300">
                                    <img src="assets/img/logo/digitalmagic-logo.png" alt="Keys to Doors Logo">
                                    <!--                                    <div class="text-warning push-10-t push-15">
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                        </div>
                                                                        <div class="h4 text-white-op push-5">Incredible value for money, highly recommended!</div>
                                                                        <div class="h6 text-gray">- Megan Dean</div>-->
                                </div>

                                <div class="col-sm-3 visibility-hidden" data-toggle="appear" data-offset="-150" data-timeout="300">
                                    <img src="assets/img/logo/fameup-logo.png" alt="Keys to Doors Logo">
                                    <!--                                    <div class="text-warning push-10-t push-15">
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                            <i class="fa fa-fw fa-star"></i>
                                                                        </div>
                                                                        <div class="h4 text-white-op push-5">Incredible value for money, highly recommended!</div>
                                                                        <div class="h6 text-gray">- Megan Dean</div>-->
                                </div>
                            </div>
                            <!-- END Section Content -->
                        </section>
                    </div>
                </div>
                <!-- END Ratings -->

                <!-- Side Feature [AUTO DIRECT MESSAGING] -->
                <div class="bg-white">
                    <section class="content content-boxed overflow-hidden">
                        <!-- Section Content -->
                        <div class="row items-push-2x push-30-t nice-copy">
                            <div class="col-lg-offset-1 col-lg-5">
                                <div class="push-100-t push-50 animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft" >
                                    <h1 class="h1 font-s48 font-w700 text-black push-10" 
                                        style='line-height: 60px;'>Greet Your<br/>New Followers</h1>
                                </div>
                                <div class="push-50 visibility-hidden animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10 animated fadeInDown">
                                        <i class="fa fa-sliders text-modern push-5-r"></i> Automated Direct Messages</h1>

                                    <p class='font-w300' style='font-size: 15px;'>MorfiX will send a personalized direct message of your setting to your new followers.</p>
                                </div>
                                <div class="visibility-hidden" data-toggle="appear" data-timeout="300" data-class="animated fadeInLeft">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10"
                                        ><i class="fa fa-check text-modern push-5-r"></i> Follow-Up Message</h1>

                                    <p class='font-w300' style='font-size: 15px;'>Increase engagement with your followers by sending a follow-up message after the welcome message base on a time delay set by you.</p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-lg-offset-1 visible-lg">
                                <img class="img-responsive visibility-hidden promo" data-toggle="appear" data-offset="-250" 
                                     data-class="animated fadeInRight" 
                                     src="assets/img/promo/function-auto-dm-promo.png" alt="Morfix Auto Direct Messaging">
                            </div>
                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                <!-- END Side Feature [AUTO DIRECT MESSAGING] -->

                <!-- Side Feature [AUTO INTERACTIONS] -->
                <div class="bg-white">
                    <section class="content content-boxed overflow-hidden">
                        <!-- Section Content -->
                        <div class="row items-push-2x push-30-t nice-copy">
                            <div class="col-lg-5 col-lg-offset-1 visible-lg">
                                <img class="img-responsive visibility-hidden promo" data-toggle="appear" data-offset="-250" 
                                     data-class="animated fadeInLeft" 
                                     src="assets/img/promo/function-follow-unfollow-promo.png" alt="Morfix Auto Interactions">
                            </div>

                            <div class="col-lg-offset-1 col-lg-5">
                                <div class="push-100-t push-50  animated fadeInRight" data-toggle="appear" data-class="animated fadeInRight" >
                                    <h1 class="h1 font-s48 font-w700 text-black push-10" 
                                        style='line-height: 60px;'>Gain Followers,
                                        <br/>Likes &
                                        <br/>Comments
                                    </h1>
                                </div>

                                <div class="push-50 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10">
                                        <i class="fa fa-sliders text-modern push-5-r"></i> Define Your Audience
                                    </h1>
                                    <p class='font-w300' style='font-size: 15px;'>
                                        5 minutes setup to let MorfiX know the audience that matters to you. Or you can choose from a few niches MorfiX handpicked for you.
                                    </p>
                                </div>

                                <div class="visibility-hidden" data-toggle="appear" data-timeout="300" data-class="animated fadeInRight">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10 animated fadeInDown" 
                                        data-toggle="appear" data-class="animated fadeInRight">
                                        <i class="fa fa-check text-modern push-5-r"></i> MorfiX Interacts With Them
                                    </h1>
                                    <p class='font-w300' style='font-size: 15px;'>
                                        You gain followers, likes and comments when MorfiX interacts with your targeted audience, bringing their attention to your profile.
                                    </p>
                                </div>
                            </div>

                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                <!-- END Side Feature [AUTO INTERACTIONS] -->

                <!-- Side Feature [POST SCHEDULING] -->
                <div class="bg-white">
                    <section class="content content-boxed overflow-hidden">
                        <!-- Section Content -->
                        <div class="row items-push-2x push-30-t nice-copy">
                            <div class="col-lg-offset-1 col-lg-5">
                                <div class="push-10-t push-50 animated fadeInLeft" data-toggle="appear" data-class="animated fadeInLeft" >
                                    <h1 class="h1 font-s48 font-w700 text-black push-10" 
                                        style='line-height: 60px;'>Save Time With No Limits Scheduling</h1>
                                </div>
                                <div class="push-50 visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10 animated fadeInDown" 
                                        ><i class="fa fa-sliders text-modern push-5-r"></i> Plan, Schedule & Post</h1>
                                    <p class='font-w300' style='font-size: 15px;'>
                                        We understand that a big part of increasing engagement on Instagram is posting content consistently.
                                        <br/>Simply upload your content and schedule a
                                        <br/>date and time and MorfiX will post your content for you.</p>
                                </div>
                                <div class="visibility-hidden" data-toggle="appear" data-timeout="300" data-class="animated fadeInLeft">
                                    <h1 class="h3 font-w700 text-black text-uppercase push-10">
                                        <i class="fa fa-check text-modern push-5-r"></i> No More Awful Looking Captions</h1>

                                    <p class='font-w300' style='font-size: 15px;'>Hashtags is an important tool to increase engagement. But too many
                                        <br/>hashtags in the captions can be awful to look at. Therefore you can set your
                                        <br/>hashtags in the comments settings and MorfiX will input your
                                        <br/>hashtags in your first comment when posting your content.</p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-lg-offset-1 visible-lg">
                                <img class="img-responsive visibility-hidden promo" data-toggle="appear" data-offset="-250" 
                                     data-class="animated fadeInRight" 
                                     src="assets/img/promo/function-post-scheduling-promo.png" alt="Morfix Post Scheduling">
                            </div>
                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                <!-- END Side Feature [POST SCHEDULING] -->

                <!-- Mini Stats -->
                <div style='background-color: rgba(0, 0, 0, 0.83);'>
                    <section class="content content-boxed">
                        <!-- Section Content -->
                        <div class="row items-push push-20-t push-20 text-center">
                            <div class="col-xs-3">
                                <div class="item item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                                    <i class="si si-anchor text-primary"></i>
                                </div>
                                <div class="h1 push-5 text-white" data-toggle="countTo" data-to="15"></div>
                                <div class="font-w600 text-uppercase text-muted text-white">NICHES</div>
                            </div>
                            <div class="col-xs-3">
                                <div class="item item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                                    <i class="si si-users text-primary"></i>
                                </div>
                                <div class="h1 push-5 text-white" data-toggle="countTo" data-to="1786"></div>
                                <div class="font-w600 text-uppercase text-muted text-white">USERS</div>
                            </div>
                            <div class="col-xs-3">
                                <div class="item item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                                    <i class="si si-screen-smartphone text-primary"></i>
                                </div>
                                <div class="h1 push-5 text-white" data-toggle="countTo" data-to="1044081"></div>
                                <div class="font-w600 text-uppercase text-muted text-white">ENGAGEMENTS</div>
                            </div>
                            <div class="col-xs-3">
                                <div class="item item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                                    <i class="si si-rocket text-primary"></i>
                                </div>
                                <div class="h1 push-5 text-white" data-toggle="countTo" data-to="4257308"></div>
                                <div class="font-w600 text-uppercase text-muted text-white">PEOPLE REACHED</div>
                            </div>
                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                <!-- END Mini Stats -->
                
                <div class="bg-gray-lighter">
                    <section class="content content-boxed">
                        <div class="push-10-t push-10 text-center">
                            <h1 class="h1 font-w700 font-s64 push-10 animated fadeInDown" 
                                data-toggle="appear" data-class="animated fadeInDown">
                                HEAR WHAT OUR FANS SAY
                            </h1>
                        </div>
                        <!-- Section Content -->
                        <div class="row items-push push-20-t push-20 text-center">
                            <div class="col-sm-3 col-lg-3">
                                <img style="width: 100%;" src="assets/img/frontpage-carousell/lmtestimonial.png" alt="Lifestyle Mafia Testimonial" />
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <img style="width: 100%;" src="assets/img/frontpage-carousell/christinehawktestimonial.png" alt="Lifestyle Mafia Testimonial" />
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <img style="width: 100%;" src="assets/img/frontpage-carousell/fitnessjunkietestimonial.png" alt="Lifestyle Mafia Testimonial" />
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <img style="width: 100%;" src="assets/img/frontpage-carousell/fameuptestimonial.png" alt="Lifestyle Mafia Testimonial" />
                            </div>
                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                
                <!-- Pricing -->
                <div style='background-color: rgba(0, 0, 0, 0.83);'>
                    <section class="content content-boxed overflow-hidden">
                        <div class="push-10-t push-10 text-center">
                            <h1 class="h1 font-w700 font-s64 push-10 animated fadeInDown text-white" 
                                data-toggle="appear" data-class="animated fadeInDown">
                                Get Started Today
                            </h1>
                        </div>
                        <div class="push-10-t push-50 text-center">
                            <h2 class="h5 font-w300 text-white-op animated fadeInDown" data-toggle="appear" data-class="animated fadeInDown">
                                Sign up now & get a free trial from us. Prices per account</h2>
                        </div>
                        <!-- Section Content -->
                        <div class="row push-20-t push-20 plans">
                            <div class="col-sm-6 col-lg-4 visibility-hidden" data-toggle="appear" data-offset="50" data-class="animated fadeInDown">
                                <!-- FREE Plan -->
                                <div class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                                    <div class="block-header" style='background-color: #292929; padding-top: 10px; 
                                         padding-left: 10px; padding-right: 10px; padding-bottom: 5px;
                                         border-bottom: none;'>
                                        <div style='padding:5px; background-color:#494949; height: 274px;'>
                                            <h3 class="push-20-t block-title h1 font-w700 text-white" 
                                                style="font-family: 'Montserrat', sans-serif; font-size: 25px;">FREE</h3>
                                                <div class="push-10-t block-title h6 text-white" 
                                             style="font-size: 14px; text-transform: none; font-weight: 100;
                                             font-style: italic; font-family: 'Lora', serif;">&nbsp;</div>
                                            <div class="push-20-t text-white" 
                                                 style="font-family: 'Montserrat', sans-serif; 
                                                 font-style: normal; font-weight: normal; height: 200px;">
                                                <span style="font-size: 30px; top: -20px; position:relative;">$</span>
                                                <span style="font-size: 60px; top: -20px; padding-left: 10px;">0</span>
                                                <div class="push-30-t block-title h6 text-white" 
                                                     style="font-size: 14px; text-transform: none; font-weight: 100;
                                                     font-style: italic; font-family: 'Lora', serif;">Free Trial Available</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-content" style='background-color: #292929; padding-top: 0px;
                                         padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>
                                        <div style='background-color:#494949;'>
                                            <div style="font-family: 'Raleway'; padding-top: 35px; font-weight: 400; 
                                                 padding-bottom: 20px; padding-left: 30px; padding-right: 30px;">
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Morfix.co Branding On Messages & Captions
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Low Speed
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Low Priority Support
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    7 days Free “Auto-like” Only (No Auto Comment, Follow/Unfollow)
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Greet New Followers With Direct Message
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Post Scheduling
                                                </p>
                                            </div>
                                            <div style='padding-top: 0px; padding-bottom: 40px;'>
                                                <a href='' class="text-white" style='
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

                            <div class="col-sm-6 col-lg-4 visibility-hidden" data-toggle="appear" data-offset="50" data-class="animated fadeInDown">
                                <!-- FREE Plan -->
                                <div class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                                    <div class="block-header" style='background-color: #292929; padding-top: 10px; 
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
                                    <div class="block-content" style='background-color: #292929; padding-top: 0px;
                                         padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>
                                        <div style='background-color:#292929;'>
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
                                                <a href='' class="text-white" style='
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

                            <div class="col-sm-6 col-lg-4 visibility-hidden" data-toggle="appear" data-offset="50" data-class="animated fadeInDown">
                                <!-- FREE Plan -->
                                <div class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                                    <div class="block-header" style='background-color: #292929; padding-top: 10px; 
                                         padding-left: 10px; padding-right: 10px; padding-bottom: 5px;
                                         border-bottom: none;'>
                                        <div style='padding:5px; background-color:#494949; height: 274px;'>
                                            <h3 class="push-20-t block-title h1 font-w700 text-white" 
                                                style="font-family: 'Montserrat', sans-serif; font-size: 25px;">BUSINESS</h3>
                                            <div class="push-10-t block-title h6 text-white" 
                                                 style="font-size: 14px; text-transform: none; font-weight: 100;
                                                 font-style: italic; font-family: 'Lora', serif;">Add-On</div>
                                            <div class="push-20-t text-white" 
                                                 style="font-family: 'Montserrat', sans-serif; 
                                                 font-style: normal; font-weight: normal; height: 200px;">
                                                <span style="font-size: 30px; top: -20px; position:relative;">$</span>
                                                <span style="font-size: 60px; top: -20px; padding-left: 10px;">97/mth</span>
                                                <div class="push-30-t block-title h6 text-white" 
                                                     style="font-size: 14px; text-transform: none; font-weight: 100;
                                                     font-style: italic; font-family: 'Lora', serif;">Free Trial Available</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-content" style='background-color: #292929; padding-top: 0px;
                                         padding-left: 10px; padding-right: 10px; padding-bottom: 10px;'>
                                        <div style='background-color:#494949;'>
                                            <div style="font-family: 'Raleway'; padding-top: 35px; font-weight: 400; 
                                                 padding-bottom: 20px; padding-left: 30px; padding-right: 30px;">
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    No Morfix.co branding
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Full Speed
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Very High Priority Support
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Auto Interaction (Like, Comment Follow, Unfollow)
                                                </p>
                                                <p style="border-bottom: 1px dotted #bdbdbd; line-height: 50px; color: rgb(255, 255, 255); text-align:center; font-size: 14px;">
                                                    Auto Engagement Group
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
                                                    5 Additional Instagram Account
                                                </p>
                                            </div>
                                            <div style='padding-top: 0px; padding-bottom: 40px;'>
                                                <a href='' class="text-white" style='
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

                        </div>
                        <!-- END Section Content -->
                    </section>
                </div>
                <!-- END Pricing -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" style='background-color: #292929;'>
                <div class="content content-boxed">
                    <!-- Copyright Info -->
                    <div class="font-s12 push-20 clearfix">
                        <hr class="remove-margin-t">
                        <div class="pull-right text-white">
                            Powered by Instaffiliates &TRADE;
                        </div>
                        <div class="pull-left text-white">
                            <span class="font-w600">Morfix 2.0</span> &copy; <span class="js-year-copy"></span>
                        </div>
                    </div>
                    <!-- END Copyright Info -->
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        <script src="assets/js/core/jquery.slimscroll.min.js"></script>
        <script src="assets/js/core/jquery.scrollLock.min.js"></script>
        <script src="assets/js/core/jquery.appear.min.js"></script>
        <script src="assets/js/core/jquery.countTo.min.js"></script>
        <script src="assets/js/core/jquery.placeholder.min.js"></script>
        <script src="assets/js/core/js.cookie.min.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="assets/js/plugins/jquery-vide/jquery.vide.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.4/typed.min.js"></script>
        <!-- Page JS Code -->
        <script>
            jQuery(function () {
                // Init page helpers (Appear + CountTo plugins)
                App.initHelpers(['appear', 'appear-countTo']);
//                function-typed
                $("#function-typed").typed({
                    strings: ["Followers Growth", "Posts Scheduling", "Direct Messages"],
                    typeSpeed: 0,
                    backDelay: 1500,
                    loop: true,
                    loopCount: false
                });
            });
        </script>
    </body>
</html>