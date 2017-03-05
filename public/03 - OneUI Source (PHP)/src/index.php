<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>

<!-- Page Container -->
<div id="page-container">
    <!-- Main Container -->
    <main id="main-container">
        <!-- Hero Content -->
        <div class="bg-video" data-vide-bg="<?php echo $one->assets_folder; ?>/img/videos/hero_tech" data-vide-options="posterType: jpg, position: 50% 75%">
            <div class="bg-primary-dark-op">
                <section class="content content-full content-boxed">
                    <!-- Section Content -->
                    <div class="text-center push-30-t visibility-hidden" data-toggle="appear" data-class="animated fadeIn">
                        <a class="fa-2x text-white" href="">
                            <i class="fa fa-circle-o-notch text-primary push-5-r"></i>neUI
                        </a>
                    </div>
                    <div class="push-100-t push-50 text-center">
                        <h1 class="h2 font-w700 text-white push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Build your Backend and Frontend with one super flexible framework.</h1>
                        <h2 class="h4 text-white-op visibility-hidden" data-toggle="appear" data-timeout="750" data-class="animated fadeIn"><em>Trusted by thousands of web developers and web agencies all over the world.</em></h2>
                        <div class="push-50-t push-20 text-center">
                            <a class="btn btn-rounded btn-noborder btn-lg btn-success push-10-r push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" href="http://goo.gl/6LF10W">
                                <i class="fa fa-shopping-cart push-10-r"></i>Purchase
                            </a>
                            <a class="btn btn-rounded btn-noborder btn-lg btn-primary push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" href="base_pages_dashboard.php">Live Preview</a>
                        </div>
                    </div>
                    <!-- END Section Content -->
                </section>
            </div>
        </div>
        <!-- END Hero Content -->

        <!-- Live Previews -->
        <div class="bg-gray-lighter">
            <section class="content content-boxed">
                <!-- Section Content -->
                <h3 class="font-w400 text-black push-30-t push-20">Live Preview</h3>
                <div class="row">
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_dashboard.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Backend</div>
                                <p class="text-muted">Explore backend pages</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2 ribbon ribbon-modern ribbon-success" href="bd_dashboard.php">
                            <div class="ribbon-box font-w600">New!</div>
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_boxed.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Boxed Backend</div>
                                <p class="text-muted">Boxed layout variation</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="javascript:void(0)">
                            <img class="img-responsive" src="assets/img/various/promo_preview_angular.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">AngularJS Version</div>
                                <p class="text-muted">It is now available!</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_home.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Frontend</div>
                                <p class="text-muted">Explore frontend pages</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_home_header_nav.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_horizontal.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Frontend Horizontal</div>
                                <p class="text-muted">With horizontal navigation</p>
                            </div>
                        </a>
                    </div>
                </div>
                <h3 class="font-w400 text-black push-50-t push-20">Backend Pages Packs</h3>
                <div class="row">
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_dashboard_v2.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_generic.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Generic</div>
                                <p class="text-muted">All kinds of pages</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_ecom_dashboard.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_ecom.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">e-Commerce</div>
                                <p class="text-muted">For your custom e-shop</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_profile_v2.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_uprofile.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">User Profile</div>
                                <p class="text-muted">For your customers</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_forum_categories.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_forums.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Forum</div>
                                <p class="text-muted">For your support</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_auth.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_auth.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Authentication</div>
                                <p class="text-muted">For your security section</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="base_pages_errors.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_backend_error.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Error Pages</div>
                                <p class="text-muted">Errors with style</p>
                            </div>
                        </a>
                    </div>
                </div>
                <h3 class="font-w400 text-black push-50-t push-20">Frontend Pages Packs</h3>
                <div class="row push-30">
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_pricing.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_generic.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Generic</div>
                                <p class="text-muted">All kinds of pages</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_blog_classic.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_blog.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Blog</div>
                                <p class="text-muted">For your custom blog</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_elearning_courses.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_elearn.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">e-Learning</div>
                                <p class="text-muted">For your learning platform</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_ecom_home.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_ecom.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">e-Commerce</div>
                                <p class="text-muted">For your digital store</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="block block-link-hover2" href="frontend_travel_agency.php">
                            <img class="img-responsive" src="assets/img/various/promo_preview_frontend_travel.jpg" alt="">
                            <div class="block-content">
                                <div class="h4 push-5">Travel</div>
                                <p class="text-muted">For your travel websites</p>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
        <!-- END Live Previews -->

        <!-- Features -->
        <div class="bg-white">
            <section class="content content-boxed">
                <!-- Section Content -->
                <div class="row items-push-3x push-50-t nice-copy">
                    <div class="col-sm-4">
                        <div class="text-center push-30">
                            <span class="item item-2x item-circle border">
                                <i class="si si-energy text-city"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">One Powerful Layout</h3>
                        <p>OneUI’s layout was built from the ground up to be flexible, lightweight and easy to use. It will enable you to build backend and frontend pages that look and work great.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push-30">
                            <span class="item item-2x item-circle border">
                                <i class="si si-rocket text-success"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Bootstrap Powered</h3>
                        <p>Bootstrap is a sleek, intuitive, and powerful mobile first front-end framework for faster and easier web development. OneUI was built on top, extending it to a large degree.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border">
                                <i class="si si-screen-smartphone text-flat"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Fully Responsive</h3>
                        <p>The User Interface will adjust to any screen size. It will look great on mobile devices and desktops at the same time. No need to worry about the UI, just stay focused on the development.</p>
                    </div>
                </div>
                <div class="row items-push-3x push-50-t nice-copy">
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border">
                                <i class="si si-badge text-warning"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">AngularJS, PHP &amp; HTML Versions</h3>
                        <p>You will get 3 versions and an extra boilerplate Get Started version, so you can choose the one that fits you best. Rocket start your project the way you want.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border">
                                <i class="si si-globe text-black"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Cross Browser Support</h3>
                        <p>OneUI will play nice with all modern browsers such as Chrome, Firefox, Safari, Opera and the latest versions of Internet Explorer (9 and up).</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border">
                                <i class="si si-clock text-info"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Save time</h3>
                        <p>OneUI will save you hundreds of hours of extra development. Start right away coding your functionality and watch your project come to life months sooner.</p>
                    </div>
                </div>
                <div class="row items-push-3x nice-copy">
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border">
                                <i class="si si-check text-success"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Frontend Pages</h3>
                        <p>Premium and fully responsive frontend pages are included in OneUI package, too. They use the same resources with the backend, so you can build your web application in one go using all available components wherever you like.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push-30">
                            <span class="item item-2x item-circle border text-amethyst">{less}</span>
                        </div>
                        <h3 class="h5 font-w600 text-center push-10">LessCSS</h3>
                        <p>OneUI was built from scratch with LessCSS. Completely modular design with components, variables and mixins that with help you customize and extend your framework to the maximum.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center push">
                            <span class="item item-2x item-circle border text-smooth">
                                <i class="si si-speedometer"></i>
                            </span>
                        </div>
                        <h3 class="h5 font-w600 text-uppercase text-center push-10">Grunt Tasks</h3>
                        <p>Grunt tasks will make your life easier. You can use them to live-compile your Less files to CSS as you work or build your custom color themes and framework.</p>
                    </div>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
        <!-- END Features -->

        <!-- Ratings -->
        <div class="bg-image" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo36@2x.jpg');">
            <div class="bg-primary-dark-op">
                <section class="content content-full content-boxed overflow-hidden">
                    <!-- Section Content -->
                    <div class="row items-push-2x push-50-t">
                        <div class="col-sm-4 visibility-hidden" data-toggle="appear">
                            <div class="text-warning push-10-t push-15">
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                            </div>
                            <div class="h4 text-white-op push-5">It's awesome, not only the design is marvelous, the code and documentation helps easy customization.</div>
                            <div class="h6 text-gray">For Design Quality by <em>alperaydyn2</em></div>
                        </div>
                        <div class="col-sm-4 visibility-hidden" data-toggle="appear" data-timeout="150">
                            <div class="text-warning push-10-t push-15">
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                            </div>
                            <div class="h4 text-white-op push-5">Awesome !!! Thanks for a so great template !!</div>
                            <div class="h6 text-gray">For Feature Availability by <em>Markuitos</em></div>
                        </div>
                        <div class="col-sm-4 visibility-hidden" data-toggle="appear" data-timeout="300">
                            <div class="text-warning push-10-t push-15">
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                                <i class="fa fa-fw fa-star"></i>
                            </div>
                            <div class="h4 text-white-op push-5">Awesome code, works really well, well documented!</div>
                            <div class="h6 text-gray">For Flexibility by <em>corverdevelopment</em></div>
                        </div>
                    </div>
                    <div class="h5 text-center visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">
                        <span class="text-gray">Would you like to read more reviews and testimonials? You can find them over at <a class="text-primary-light" href="http://goo.gl/6LF10W">OneUI page on Themeforest</a>.</span>
                    </div>
                    <!-- END Section Content -->
                </section>
            </div>
        </div>
        <!-- END Ratings -->

        <!-- Get Started -->
        <div class="bg-white">
            <section class="content content-full content-boxed">
                <!-- Section Content -->
                <div class="push-20-t push-20 text-center">
                    <a class="btn btn-rounded btn-noborder btn-lg btn-success push-10-r push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInLeft" href="http://goo.gl/6LF10W">
                        <i class="fa fa-shopping-cart push-10-r"></i>Purchase
                    </a>
                    <a class="btn btn-rounded btn-noborder btn-lg btn-primary push-5 visibility-hidden" data-toggle="appear" data-class="animated fadeInRight" href="base_pages_dashboard.php">Live Preview</a>
                    <h3 class="h5 push-50-t visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Crafted with <i class="fa fa-heart text-city"></i> by <a href="http://goo.gl/vNS3I">pixelcave</a></h3>
                </div>
                <!-- END Section Content -->
            </section>
        </div>
        <!-- END Get Started -->
    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/jquery-vide/jquery.vide.min.js"></script>
<script>
    jQuery(function(){
        // Init page helpers (Appear plugin)
        App.initHelpers('appear');
    });
</script>

<?php require 'inc/views/template_footer_end.php'; ?>
