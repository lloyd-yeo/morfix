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
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-88362519-1', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body style='margin:0px;'>
        <main id="main-container" style='min-height: 100%; 
              background-image: url("../assets/img/promo/vsl-bg.jpg"); 
              background-color: rgba(0,0,0,0.4); background-size:cover;'>
            <div class='col-lg-12 push-20-t'>
                <div class='col-lg-10 col-lg-offset-1'>
<!--                    <p>
                        <a href='javascript:void(0);'>
                            <img class='center-block' src="../assets/img/promo/vsl-instaffiliates-logo.png" style='width:180px; height:180px;'>
                        </a>
                    </p>-->
                    <h1 class='center-block text-center font-w700 push text-white push-20-t' 
                        style='text-shadow: 2px 4px 3px rgba(0,0,0,0.3); font-size: 52px; line-height: 0.9; max-width:910px;'>
                        INSTAGRAM<br/>LOOPHOLE SYSTEM
                    </h1>
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-30-t'>
                <center>
                    <div id='vid-container' class='center-block' style='max-width:910px;'>
                        <iframe src="https://player.vimeo.com/video/198823397" 
                                width="640" height="400" frameborder="0" 
                                webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                </center>
            </div>
            
            <div class='col-lg-10 col-lg-offset-1 push-50-t'>
                <div class='center-block' style='max-width:520px;'>
                    <button class="signup-btn start-stop-btn btn btn-block btn-lg" style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 20px;">
                        <span id="signup-btn-text">GET INSTANT ACCESS</span>
                    </button>
                </div>
            </div>
            
            <div class='col-lg-12 push-50-t' style="padding-left: 0px; padding-right: 0px;">
            </div>
            
            @include('optin.style1modal')
            
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
    <script>
            jQuery(function () {
                
                jQuery('#modal-payment').modal('show');
                
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

                    var newWidth = $fluidEl.width() / 1.5;

                    // Resize all videos according to their own aspect ratio
                    $allVideos.each(function () {

                        var $el = $(this);
                        $el
                                .width(newWidth)
                                .height(newWidth * $el.data('aspectRatio'));

                    });

                    // Kick off one resize to fix all videos on page load
                }).resize();
                
            });
    </script>
</html>