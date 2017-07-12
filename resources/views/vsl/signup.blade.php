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

        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="../assets/js/plugins/slick/slick.min.css">
        <link rel="stylesheet" href="../assets/js/plugins/slick/slick-theme.min.css">

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" id="css-main" href="../assets/css/oneui.css">
        <link rel="stylesheet" id="css-main" href="../assets/css/app.css">
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
            
        </style>
        <!-- Scripts -->
        <script>
        </script>
    </head>
    <body style='margin:0px;'>
        <main id="main-container" style='min-height: 100%; background-image: url("../assets/img/promo/vsl-bg.jpg"); background-color: rgba(0,0,0,1); background-size:cover;'>
            <div class='col-lg-12 push-50-t'>
                <div class='col-lg-10 col-lg-offset-1'>
                    <p>
                        <a href='https://morfix.co'>
                            <img class='center-block' src="../assets/img/promo/vsl-instaffiliates-logo.png" style='width:180px; height:180px;'>
                        </a>
                    </p>
                    <h1 class='center-block text-center font-w700 push text-white push-30-t' style='text-shadow: 2px 4px 3px rgba(0,0,0,0.3); font-size: 62px; line-height: 0.9; max-width:910px;'>
                        DISCOVER HOW TO CREATE<br/>HUGE AUTOMATED INCOME ON<br/>SOCIAL MEDIA EVEN IF YOU HAVE NO EXPERIENCE
                    </h1>
                </div>
            </div>
            <div class='col-lg-10 col-lg-offset-1 push-30-t'>
                <div id='vid-container' class='center-block' style='max-width:910px;'>
                    <iframe src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
                            <div class="js-slider text-center" data-slider-autoplay="true" data-slider-dots="true" data-slider-arrows="true" data-slider-num="3">
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar1.jpg" alt="">
                                    <div class="push-10-t font-w600">Rebecca Gray</div>
                                    <div class="font-s13 text-muted">Web Designer</div>
                                </div>
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar2.jpg" alt="">
                                    <div class="push-10-t font-w600">Sara Holland</div>
                                    <div class="font-s13 text-muted">Font Designer</div>
                                </div>
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar3.jpg" alt="">
                                    <div class="push-10-t font-w600">Megan Dean</div>
                                    <div class="font-s13 text-muted">Artist</div>
                                </div>
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar4.jpg" alt="">
                                    <div class="push-10-t font-w600">Ashley Welch</div>
                                    <div class="font-s13 text-muted">Graphic Designer</div>
                                </div>
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar5.jpg" alt="">
                                    <div class="push-10-t font-w600">Amanda Powell</div>
                                    <div class="font-s13 text-muted">Photographer</div>
                                </div>
                                <div>
                                    <img class="img-avatar" src="assets/img/avatars/avatar6.jpg" alt="">
                                    <div class="push-10-t font-w600">Tiffany Kim</div>
                                    <div class="font-s13 text-muted">Web Developer</div>
                                </div>
                            </div>
                            <!-- END Slider with Multiple Slides/Avatars -->
                        </div>
                    </div>
                    <!-- END Multiple Avatars -->
                </div>
            </div>
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
    
    <!-- Page JS Code -->
    <script>
        jQuery(function () {
            
            App.initHelpers('slick');
            
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

        });
    </script>

</html>