<?php require 'inc/config.php'; require 'inc/frontend_config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/magnific-popup/magnific-popup.min.css">

<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/frontend_head.php'; ?>

<!-- Hero Content -->
<!-- jQuery Vide for video backgrounds, for more examples you can check out https://github.com/VodkaBears/Vide -->
<div class="bg-video" data-vide-bg="<?php echo $one->assets_folder; ?>/img/videos/hero_sunrise" data-vide-options="posterType: jpg">
    <div class="bg-black-op">
        <!-- Header -->
        <section class="content content-full content-boxed">
            <div class="push-200-t push-200 text-center">
                <h1 class="font-s48 font-w700 text-uppercase text-white push-10 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown">Europe Travel Guide</h1>
                <h2 class="h3 font-w400 text-white-op push-50 visibility-hidden" data-toggle="appear" data-class="animated fadeInDown" data-timeout="500">The best tips to experience the incredible.</h2>
                <a class="btn btn-rounded btn-noborder btn-lg btn-success visibility-hidden" data-toggle="appear" data-class="animated fadeInUp" data-timeout="750" href="javascript:void(0)">
                    <i class="fa fa-shopping-cart push-5-r"></i> Purchase $39
                </a>
            </div>
        </section>
        <!-- END Header -->
    </div>
</div>
<!-- END Hero Content -->

<!-- Mini Stats -->
<div class="bg-gray-lighter">
    <section class="content content-boxed">
        <!-- Section Content -->
        <div class="row items-push push-20-t push-20 text-center">
            <div class="col-xs-6 col-sm-3">
                <div class="item item-2x item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                    <i class="fa fa-location-arrow text-amethyst"></i>
                </div>
                <div class="h1 push-5" data-toggle="countTo" data-to="32600" data-after="+"></div>
                <div class="font-w600 text-uppercase text-muted">Places</div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="item item-2x item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                    <i class="fa fa-cutlery text-flat"></i>
                </div>
                <div class="h1 push-5" data-toggle="countTo" data-to="3500" data-after="+"></div>
                <div class="font-w600 text-uppercase text-muted">Restaurants</div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="item item-2x item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                    <i class="fa fa-institution text-city"></i>
                </div>
                <div class="h1 push-5" data-toggle="countTo" data-to="4900" data-after="+"></div>
                <div class="font-w600 text-uppercase text-muted">Museums</div>
            </div>
            <div class="col-xs-6 col-sm-3">
                <div class="item item-2x item-circle bg-white border push-10 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated bounceIn">
                    <i class="fa fa-lightbulb-o text-warning"></i>
                </div>
                <div class="h1 push-5" data-toggle="countTo" data-to="1400" data-after="+"></div>
                <div class="font-w600 text-uppercase text-muted">Tips &amp; Tricks</div>
            </div>
        </div>
        <!-- END Section Content -->
    </section>
</div>
<!-- END Mini Stats -->

<!-- Features -->
<div class="bg-white overflow-hidden">
    <section class="content content-full content-boxed">
        <!-- Section Content -->
        <div class="text-center push-50-t push-50">
            <div>It's one of the most complete travel guides out there.</div>
            <h2 class="h1 text-black">Learn all the secrets before even getting there.</h2>
        </div>
        <div class="row items-push-2x text-center push-20">
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo37.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Cheapest Flights Available</h3>
            </div>
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo39.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Most Amazing Hotels</h3>
            </div>
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo38.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Secret Places</h3>
            </div>
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo35.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Best Museums</h3>
            </div>
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo14.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Best Mountain Hikes</h3>
            </div>
            <div class="col-sm-4">
                <div class="item item-3x item-circle bg-image push-20 visibility-hidden" data-toggle="appear" data-class="animated fadeIn" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo12.jpg');"></div>
                <h3 class="h5 font-w600 text-uppercase visibility-hidden" data-toggle="appear" data-class="animated fadeInUp">Nightlife Essentials</h3>
            </div>
        </div>
        <!-- END Section Content -->
    </section>
</div>
<!-- END Features -->

<!-- Images -->
<div class="bg-gray-lighter overflow-hidden">
    <section class="content content-full content-boxed">
        <!-- Section Content -->
        <div class="text-center push-50-t push-50">
            <div>See for yourself how it feels to be there.</div>
            <h2 class="h1 text-black">Amazing High Quality Photos.</h2>
        </div>

        <!-- Gallery (.js-gallery class is initialized in App() -> uiHelperMagnific()) -->
        <!-- For more info and examples you can check out http://dimsemenov.com/plugins/magnific-popup/ -->
        <div class="content-grid push-50">
            <div class="row js-gallery">
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo38@2x.jpg">
                        <?php $one->get_photo(38, true, 'img-responsive'); ?>
                    </a>
                </div>
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo35@2x.jpg">
                        <?php $one->get_photo(35, false, 'img-responsive'); ?>
                    </a>
                </div>
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo28@2x.jpg">
                        <?php $one->get_photo(28, false, 'img-responsive'); ?>
                    </a>
                </div>
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo37@2x.jpg">
                        <?php $one->get_photo(37, true, 'img-responsive'); ?>
                    </a>
                </div>
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo27@2x.jpg">
                        <?php $one->get_photo(27, false, 'img-responsive'); ?>
                    </a>
                </div>
                <div class="col-xs-4 animated fadeIn">
                    <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo30@2x.jpg">
                        <?php $one->get_photo(30, false, 'img-responsive'); ?>
                    </a>
                </div>
            </div>
        </div>
        <!-- END Gallery -->
        <!-- END Section Content -->
    </section>
</div>
<!-- END Images -->

<!-- Purchase -->
<div class="bg-white">
    <section class="content content-boxed overflow-hidden">
        <!-- Section Content -->
        <div class="text-center push-50-t push-50">
            <div>Get your own copy today.</div>
            <h2 class="h1 text-black">Choose your package.</h2>
        </div>
        <div class="row push-50">
            <div class="col-sm-6 col-md-3 col-md-offset-3 visibility-hidden" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                <!-- Starter -->
                <a class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-header">
                        <h3 class="block-title">Starter</h3>
                    </div>
                    <div class="block-content block-content-full bg-gray-lighter">
                        <div class="h1 font-w700 push-10">$39</div>
                        <div class="h5 font-w300 text-muted">One time payment</div>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-condensed">
                            <tbody>
                                <tr>
                                    <td><strong>Digital</strong> Travel Guide</td>
                                </tr>
                                <tr>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="block-content block-content-mini block-content-full bg-gray-lighter">
                        <span class="btn btn-default"><i class="fa fa-shopping-cart push-5-r"></i>Purchase</span>
                    </div>
                </a>
                <!-- END Starter -->
            </div>
            <div class="col-sm-6 col-md-3 visibility-hidden" data-toggle="appear" data-offset="50" data-timeout="200" data-class="animated fadeIn">
                <!-- Premium -->
                <a class="block block-bordered block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-header">
                        <h3 class="block-title text-success">Premium</h3>
                    </div>
                    <div class="block-content block-content-full bg-gray-lighter">
                        <div class="h1 font-w700 text-success push-10">$99</div>
                        <div class="h5 font-w300 text-muted">One time payment</div>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-condensed">
                            <tbody>
                                <tr>
                                    <td><strong>Digital</strong> Travel Guide</td>
                                </tr>
                                <tr>
                                    <td><strong>Printed</strong> Travel Guide</td>
                                </tr>
                                <tr>
                                    <td><strong>Travel Deals</strong> Access</td>
                                </tr>
                                <tr>
                                    <td><strong>Custom</strong> T-Shirt</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="block-content block-content-mini block-content-full bg-gray-lighter">
                        <span class="btn btn-success"><i class="fa fa-shopping-cart push-5-r"></i>Purchase</span>
                    </div>
                </a>
                <!-- END Premium -->
            </div>
        </div>
        <!-- END Section Content -->
    </section>
</div>
<!-- END Purchase -->

<!-- Ratings -->
<div class="bg-image" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo28@2x.jpg');">
    <div class="bg-black-op">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="row items-push-2x push-50-t text-center">
                <div class="col-sm-4 visibility-hidden" data-toggle="appear" data-offset="-150">
                    <?php $one->get_avatar(0, 'female', 64, true); ?>
                    <div class="text-warning push-10-t push-15">
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                    </div>
                    <div class="h4 text-white-op push-5">One of the best travel guides I have ever read! Highly recommended!</div>
                    <div class="h6 text-gray">- <?php $one->get_name('female'); ?></div>
                </div>
                <div class="col-sm-4 visibility-hidden" data-toggle="appear" data-offset="-150" data-timeout="150">
                    <?php $one->get_avatar(0, 'male', 64, true); ?>
                    <div class="text-warning push-10-t push-15">
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                    </div>
                    <div class="h4 text-white-op push-5">Amazing photos and travel tips! I can’t travel to Europe without it again!</div>
                    <div class="h6 text-gray">- <?php $one->get_name('male'); ?></div>
                </div>
                <div class="col-sm-4 visibility-hidden" data-toggle="appear" data-offset="-150" data-timeout="300">
                    <?php $one->get_avatar(0, 'female', 64, true); ?>
                    <div class="text-warning push-10-t push-15">
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                        <i class="fa fa-fw fa-star"></i>
                    </div>
                    <div class="h4 text-white-op push-5">Incredible value for money, highly recommended!</div>
                    <div class="h6 text-gray">- <?php $one->get_name('female'); ?></div>
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
</div>
<!-- END Ratings -->

<?php require 'inc/views/frontend_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/jquery-vide/jquery.vide.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/magnific-popup/magnific-popup.min.js"></script>

<!-- Page JS Code -->
<script>
    jQuery(function(){
        // Init page helpers (Appear + CountTo + Magnific Popup plugins)
        App.initHelpers(['appear', 'appear-countTo', 'magnific-popup']);
    });
</script>

<?php require 'inc/views/template_footer_end.php'; ?>