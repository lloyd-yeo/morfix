<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="{{ config('app.locale') }}"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="{{ config('app.locale') }}"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <title>{{ config('app.name', 'Morfix') }}</title>

        <meta name="description" content="Morfix - The Best Instagram Growth Hacking Tool">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="../assets/img/favicons/favicon.png">

        <link rel="icon" type="image/png" href="../assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="../assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="../assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="../assets/img/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="../assets/img/favicons/favicon-192x192.png" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="../assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,200,300,400,400italic,600,700%7COpen+Sans:100,200,300,400,400italic,600,700">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,400i|Montserrat:400,900|Raleway">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat%3A400%2C700%7CRaleway%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900%7CDroid+Serif%3A400%2C700%2C400italic%2C700italic%7CLora%3A400%2C700%2C400italic%2C700italic&amp;subset=latin&amp;ver=1479142963">

        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="../assets/js/plugins/slick/slick.min.css">
        <link rel="stylesheet" href="../assets/js/plugins/slick/slick-theme.min.css">

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" id="css-main" href="../assets/css/oneui.css">
        <link rel="stylesheet" id="css-main" href="../assets/css/app.css">
        <link rel="stylesheet" href="../assets/css/ronneby/assets/css/visual-composer.css">
        <link rel="stylesheet" href="../assets/css/ronneby/assets/css/mobile-responsive.css">
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="../assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
        <style>
            .start-stop-btn {
                background-color: #FF6A5C; 
                color: white;
                /*border: 1px solid #0C5F94;*/
            }
            .start-stop-btn:hover {
                background-color: #E55F53;
                color:white;
                cursor:pointer;
            }

            /* Large desktops and laptops */
            @media (min-width: 1200px) {
                #landing-txt { font-size: 52px; }
                #signup-btn { height: 60px; }
                #form-signup { max-width: 90%; margin-top: 0px; }
                #review-container { max-width: 100%; margin-top: 0px; }
                #logo-container { margin-top: 50px; }
                #logo-img { width:180px; height: 180px; }
                #signup-btn-text { font-size: 20px; font-weight: bold; }
            }

            /* Landscape tablets and medium desktops */
            @media (min-width: 992px) and (max-width: 1199px) {
                #landing-txt { font-size: 52px; }
                #signup-btn { height: 60px; }
                #form-signup { max-width: 90%; margin-top: 0px; }
                #review-container { max-width: 100%; margin-top: 0px; }
                #logo-container { margin-top: 50px; }
                #logo-img { width:180px; height: 180px; }
                #signup-btn-text { font-size: 20px; font-weight: bold; }
            }

            /* Portrait tablets and small desktops */
            @media (min-width: 768px) and (max-width: 991px) {
                #landing-txt { font-size: 52px; }
                #signup-btn { height: 40px; }
                #form-signup { max-width: 90%; margin-top: 0px; }
                #review-container { max-width: 100%; margin-top: 0px; }
                #logo-container { margin-top: 50px; }
                #logo-img { width:180px; height: 180px; }
                #signup-btn-text { font-size: 20px; font-weight: bold; }
            }

            /* Landscape phones and portrait tablets */
            @media (max-width: 767px) {
                #landing-txt { font-size: 32px; }
                #signup-btn { height: 38px; }
                #form-signup { max-width: 100%; margin-top: 0px; }
                #review-container { max-width: 100%; margin-top: 0px; }
                #vid-container { padding: 15px 7px 10px 7px; }
                #logo-container { margin-top: 30px; }
                #logo-img { width:120px; height: 120px; }
                #signup-btn-text { font-size: 15px; font-weight: bold; }
            }

            /* Portrait phones and smaller */
            @media (max-width: 480px) {
                #landing-txt { font-size: 32px; }
                #signup-btn { height: 38px; }
                #form-signup { max-width: 100%; margin-top: 0px; }
                #review-container { max-width: 100%; margin-top: 0px; }
                #vid-container { padding: 15px 7px 10px 7px; }
                #logo-container { margin-top: 30px; }
                #logo-img { width: 120px; height: 120px; }
                #signup-btn-text { font-size: 15px; font-weight: bold; }
            }

            /*Filter styles*/
            .saturate {-webkit-filter: saturate(3); filter: saturate(3);}
            .grayscale {-webkit-filter: grayscale(100%); filter: grayscale(100%);}
            .contrast {-webkit-filter: contrast(160%); filter: contrast(160%);}
            .brightness {-webkit-filter: brightness(0.25); filter: brightness(0.25);}
            .blur {-webkit-filter: blur(3px); filter: blur(3px);}
            .invert {-webkit-filter: invert(100%); filter: invert(100%);}
            .sepia {-webkit-filter: sepia(100%); filter: sepia(100%);}
            .huerotate {-webkit-filter: hue-rotate(180deg); filter: hue-rotate(180deg);}
            .rss.opacity {-webkit-filter: opacity(50%); filter: opacity(50%);}

            /**
            * The CSS shown here will not be introduced in the Quickstart guide, but shows
            * how you can use CSS to style your Element's container.
            */
            .StripeElement {
                background-color: white;
                padding: 8px 12px;
                border-radius: 4px;
                border: 1px solid transparent;
                box-shadow: 0 1px 3px 0 #e6ebf1;
                -webkit-transition: box-shadow 150ms ease;
                transition: box-shadow 150ms ease;
            }

            .StripeElement--focus {
                box-shadow: 0 1px 3px 0 #cfd7df;
            }

            .StripeElement--invalid {
                border-color: #fa755a;
            }

            .StripeElement--webkit-autofill {
                background-color: #fefde5 !important;
            }

        </style>
        <!-- Scripts -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-88362519-1', 'auto');
            ga('send', 'pageview');
          </script>
    </head>
    <body style='margin:0px;'>
        <main id="main-container" style='min-height: 100%; background-image: url("../assets/img/promo/vsl-bg.jpg"); background-color: rgba(0,0,0,1); background-size:cover;'>
            <div class='col-lg-12 push-50-t'>
                <div class='col-lg-10 col-lg-offset-1'>
                    @elseif ($redir == "ebook")
                    <p>Your copy of the free e-book has been sent to your email.</p>
                    @endif
                    <p>
                        <a href='javascript:void(0);'>
                            <img class='center-block' src="../assets/img/promo/vsl-instaffiliates-logo.png" style='width:180px; height:180px;'>
                        </a>
                    </p>
                    <h1 class='center-block text-center font-w700 push text-white push-30-t' style='text-shadow: 2px 4px 3px rgba(0,0,0,0.3); font-size: 62px; line-height: 0.9; max-width:910px;'>
                        @if ($redir == "home")
                        DISCOVER HOW TO CREATE<br/>HUGE AUTOMATED INCOME ON<br/>SOCIAL MEDIA EVEN IF YOU HAVE NO EXPERIENCE
                        @elseif ($redir == "vsl")
                        DISCOVER HOW TO CREATE<br/>HUGE AUTOMATED INCOME ON<br/>SOCIAL MEDIA EVEN IF YOU HAVE NO EXPERIENCE
                        @elseif ($redir == "mmovsl")
                        DISCOVER HOW TO CREATE<br/>HUGE AUTOMATED INCOME ON<br/>SOCIAL MEDIA EVEN IF YOU HAVE NO EXPERIENCE
                        @elseif ($redir == "ospvsl")
                        YOUR OSP BUSINESS WILL<br/>GROW FASTER WITH THIS<br/>INSTAGRAM LOOPHOLE<br/>SYSTEM
                        @elseif ($redir == "davsl")
                        YOUR DIGITAL ALTITUDE<br/> BUSINESS WILL GROW <br/>FASTER WITH THIS<br/>INSTAGRAM LOOPHOLE<br/>SYSTEM
                        @elseif ($redir == "mcavsl")
                        YOUR MCA BUSINESS WILL<br/>GROW FASTER WITH THIS<br/>INSTAGRAM LOOPHOLE<br/>SYSTEM
                        @elseif ($redir == "rcvsl")
                        YOUR REVERSE COMMISSION<br/> BUSINESS WILL GROW <br/>FASTER WITH THIS<br/>INSTAGRAM LOOPHOLE<br/>SYSTEM
                        @elseif ($redir == "mlm")
                        YOUR MLM BUSINESS WILL<br/>GROW FASTER WITH THIS<br/>INSTAGRAM LOOPHOLE<br/>SYSTEM
                        @elseif ($redir == "online")
                        GET THOUSANDS OF LEADS FOR YOUR ONLINE BUSINESS WITH THIS #1 INSTAGRAM GROWTH HACKING TOOL
                        @elseif ($redir == "ebook")
                        DISCOVER HOW TO CREATE<br/>HUGE AUTOMATED INCOME ON<br/>SOCIAL MEDIA EVEN IF YOU HAVE NO EXPERIENCE
                        @elseif ($redir == "tool")
                        DISCOVER HOW TO GROW YOUR<br/>INSTAGRAM FASTER<br/>WITH THIS AUTOMATION TOOL
                        @endif
                    </h1>
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-30-t'>
                <div id='vid-container' class='center-block' style='max-width:910px;'>

                    @if ($redir == "mmovsl")
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "vsl")
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "home")
                    <iframe src="https://player.vimeo.com/video/199027287" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "ospvsl")
                    <iframe src="https://player.vimeo.com/video/199027287" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "davsl")
                    <iframe src="https://player.vimeo.com/video/199028557" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "mcavsl")
                    <iframe src="https://player.vimeo.com/video/199100363" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "rcvsl")
                    <iframe src="https://player.vimeo.com/video/199101398" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "mlm")
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "online")
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "ebook")
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @elseif ($redir == "tool")
                    <iframe src="https://player.vimeo.com/video/198823397"
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @endif
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-50-t'>
                <div class='center-block' style='max-width:910px;'>
                    <button class="signup-btn start-stop-btn btn btn-block btn-lg" style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 20px;">
                        <span id="signup-btn-text">GET INSTANT ACCESS</span>
                    </button>
                </div>
            </div>
            <div class='col-lg-12 push-50-t' style="padding-left: 0px; padding-right: 0px;">
                <div class='center-block' style='background-color:black;'>
                    <p class="text-center font-s48 text-white" style='font-weight:900; padding-top: 60px; padding-bottom: 60px;'>
                        Spy on what our users think!
                    </p>
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-50-t'>
                <div class='center-block'>
                    <!-- Multiple Avatars -->
                    <div class="block" style='background-color: transparent;'>
                        <div class="block-header">
                            <h3 class="center-block text-center font-w700 push text-white" style='text-shadow: 2px 4px 3px rgba(0,0,0,0.3);'>Swipe left to see more happy users!</h3>
                        </div>
                        <div class="block-content">
                            <!-- Slider with Multiple Slides/Avatars -->
                            <div class="js-slider text-center" data-slider-autoplay="true" data-slider-dots="true" data-slider-arrows="true" 
                                 data-slider-num="4">
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1830.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1828.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1823.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1825.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1826.JPG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1829.JPG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1741.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1820.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                                <div>
                                    <img class="img-responsive" src="https://morfix.co/app/testimonials/IMG_1822.PNG?v=1" alt="" style='width:91%;'>
                                </div>
                            </div>
                            <!-- END Slider with Multiple Slides/Avatars -->
                        </div>
                    </div>
                    <!-- END Multiple Avatars -->
                </div>
                <div class='center-block' style='max-width:910px;'>
                    <button class="signup-btn start-stop-btn btn btn-block btn-lg" style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 20px;">
                        <span id="signup-btn-text">GET INSTANT ACCESS</span>
                    </button>
                </div>
            </div>

            <div class='col-lg-12 push-50-t' style="padding-left: 0px; padding-right: 0px;">
                <div class='center-block' style='background-color:black;'>
                    <p class="text-center font-s48 text-white" style='font-weight:900; padding-top: 60px; padding-bottom: 60px;'>
                        When you sign up, you'll get...
                    </p>
                </div>
            </div>

            <div class='col-lg-10 col-lg-offset-1 push-50-t' style='font-size: 26px; text-align:center; color:white; font-weight: 700;'>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-1.png' class="invert" style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Automated<br/>Follower's Growth</span>
                </div>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-2.png' class='invert' style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Automated<br/>Direct Messages</span>
                </div>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-3.png' class='invert' style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Scheduled Post +<br/>Content created for you!</span>
                </div>
            </div>

            <div class='col-lg-10 col-lg-offset-1 push-50-t' style='font-size: 26px; text-align:center; color:white; font-weight: 700;'>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-4.svg' class='invert' style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Access to<br/>secret group community</span>
                </div>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-5.png' class='invert' style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Instagram<br/>Trainings</span>
                </div>
                <div class='col-lg-4 mt'>
                    <img src='../assets/img/promo/vsl-feature-6.png' class='invert' style='width: 50px;'/>
                    <br/>
                    <span class='mt'>Sales & Affiliate<br/>Marketing Training</span>
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-50-t push-50'>
                <div class='center-block' style='max-width:910px;'>
                    <button class="signup-btn start-stop-btn btn btn-block btn-lg" style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 20px;">
                        <span id="signup-btn-text">GET INSTANT ACCESS</span>
                    </button>
                </div>
            </div>

            @include('vsl.modal')

        </main>
    </body>

    <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/core/jquery.slimscroll.min.js"></script>
    <script src="../assets/js/core/jquery.scrollLock.min.js"></script>
    <script src="../assets/js/core/jquery.appear.min.js"></script>
    <script src="../assets/js/core/jquery.countTo.min.js"></script>
    <script src="../assets/js/core/jquery.placeholder.min.js"></script>
    <script src="../assets/js/core/js.cookie.min.js"></script>
    <script src="../assets/js/app.js"></script>
    <script src="../assets/js/plugins/jquery-vide/jquery.vide.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/1.1.4/typed.min.js"></script>

    <!-- Page Plugins -->
    <script src="../assets/js/plugins/slick/slick.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.stripe.com/v2/"></script>
    <!-- Page JS Code -->
    <script>
            var $plan = 1;
            var $paymentMethod = 1;

            jQuery(function () {

                App.initHelpers('slick');
                Stripe.setPublishableKey('pk_live_WrvnbbOwMxU7FwZzaoTdaUpa');
                //Stripe.setPublishableKey('pk_test_9AIw34u0sCHRPJIjOyFh19LN');

                var $allVideos = $("iframe[src^='https://player.vimeo.com']"),
                        // The element that is fluid width
                        //                $fluidEl = $("body");
                        $fluidEl = $("#vid-container");

                // Figure out and save aspect ratio for each video
                $allVideos.each(function () {
                    console.log("aspectRatio " + (this.height / this.width));
                    $(this).data('aspectRatio', this.height / this.width)

                            // and remove the hard coded width/height
                            .removeAttr('height')
                            .removeAttr('width');

                });

                // When the window is resized
                $(window).resize(function () {

                    var newWidth = $fluidEl.width();

                    // Resize all videos according to their own aspect ratio
                    $allVideos.each(function () {

                        var $el = $(this);
                        $el
                                .width(newWidth)
                                .height(newWidth * $el.data('aspectRatio'));

                    });

                    // Kick off one resize to fix all videos on page load
                }).resize();

                $(".signup-btn").on("click", function () {
                    jQuery('#modal-payment').modal('show');
                });

                $("#plan-dropdown").on("change", function (e) {
                    $("select.plan-dropdown option:selected").each(function () {
                        var $selectedOpt = $(this).val();
                        $plan = $selectedOpt;
                        console.log($selectedOpt);
                        if ($selectedOpt === "1") {
                            $("#pro-pkg").fadeOut("slow", function () {
                                $("#premium-pkg").fadeIn("slow");
                            });
                        } else if ($selectedOpt === "2") {
                            $("#premium-pkg").fadeOut("slow", function () {
                                $("#pro-pkg").fadeIn("slow");
                            });
                        }
                    });
                });

                $("#payment-method-dropdown").on("change", function (e) {
                    $("select.payment-method-dropdown option:selected").each(function () {
                        var $selectedOptPayment = $(this).val();
                        $paymentMethod = $selectedOptPayment;
                        console.log($selectedOptPayment);
                        if ($selectedOptPayment === "1") {
                            $("#paypal-group").fadeOut("slow", function () {
                                $("#stripe-card-group").fadeIn("slow");
                                $("#stripe-logo").fadeIn("slow");
                                $("#stripe-card-btn").fadeIn("slow");
                            });
                        } else if ($selectedOptPayment === "2") {
                            $("#stripe-card-group").fadeOut("slow", function () {
                                $("#paypal-group").fadeIn("slow");
                            });
                            $("#stripe-logo").fadeOut("slow");
                            $("#stripe-card-btn").fadeOut("slow");
                        }
                    });
                });

                // Create a Stripe client
                var stripe = Stripe('pk_live_WrvnbbOwMxU7FwZzaoTdaUpa');
                //var stripe = Stripe('pk_test_9AIw34u0sCHRPJIjOyFh19LN');

                // Create an instance of Elements
                var elements = stripe.elements();

                // Custom styling can be passed to options when creating an Element.
                // (Note that this demo uses a wider set of styles than the guide below.)
                var style = {
                    base: {
                        color: '#32325d',
                        lineHeight: '24px',
                        fontFamily: 'Helvetica Neue',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                };

                // Create an instance of the card Element
                var card = elements.create('card', {style: style});

                // Add an instance of the card Element into the `card-element` <div>
                card.mount('#card-element');

                // Handle form submission
                var $form = document.getElementById('payment-form');

                $form.addEventListener('submit', function (event) {

                    event.preventDefault();
                    $form = $($form);
                    if (!validateEmail($("#signup-email").val())) {
                        event.preventDefault();
                    } else {
                        var pw1 = $("#signup-pw").val();
                        var pw2 = $("#signup-pw2").val();
                        var name = $("#signup-name").val();
                        if (!pw1 || !pw2) {
                            event.preventDefault();
                            alert("Empty passwords are not allowed.");
                        } else if (!name) {
                            event.preventDefault();
                            alert("Please enter your name.");
                        } else if (pw1 !== pw2) {
                            event.preventDefault();
                            alert("Your passwords do not match.");
                        } else {
                            // Disable the submit button to prevent repeated clicks:
                            $form.find('.submit').prop('disabled', true);
                        }
                    }

                    if ($paymentMethod === 1) { //pay via Stripe
                        stripe.createToken(card).then(function (result) {
                            if (result.error) {
                                // Inform the user if there was an error
                                var errorElement = document.getElementById('card-errors');
                                errorElement.textContent = result.error.message;
                            } else {
                                // Send the token to your server
                                stripeTokenHandler(result.token);
                            }
                        });
                    } else { //pay via Paypal
                        $form.submit();
                    }
                });

                $("#paypal-btn").on("click", function (event) {
                    event.preventDefault();
                    $paymentMethod = 2;
                    var $form = $('#payment-form');
                    $form.attr("action", $(this).attr("href"));
                    paypalHandler();
                });
                
                function paypalHandler() {
                    var form = document.getElementById('payment-form');
                    form.submit();
                }

                function stripeTokenHandler(token) {
                    // Insert the token ID into the form so it gets submitted to the server
                    var form = document.getElementById('payment-form');
                    
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    
                    var hiddenInput2 = document.createElement('input');
                    hiddenInput2.setAttribute('type', 'hidden');
                    hiddenInput2.setAttribute('name', 'plan');
                    hiddenInput2.setAttribute('value', $plan);
                    
                    form.appendChild(hiddenInput);
                    form.appendChild(hiddenInput2);
                    
                    // Submit the form
                    form.submit();
                }


                function validateEmail(mail) {
                    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
                    {
                        return true;
                    }
                    alert("You have entered an invalid email address!");
                    return false;
                }
            });
    </script>

</html>