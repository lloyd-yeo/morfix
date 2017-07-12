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
                    
                    <iframe class='center-block' src="https://player.vimeo.com/video/198823397" 
                            width="640" height="400" frameborder="0" 
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

                </div>
            </div>
        </main>
    </body>

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