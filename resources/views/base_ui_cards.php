<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/slick/slick.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/slick/slick-theme.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/magnific-popup/magnific-popup.min.css">

<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row font-s13">
        <div class="col-lg-4">
            <!-- Article -->
            <div class="block">
                <div class="block-content block-content-full">
                    <a class="font-w600" href="javascript:void(0)"><?php $one->get_name(); ?></a> just posted an article.
                </div>
                <?php $one->get_photo(27, false, 'img-responsive'); ?>
                <div class="block-content block-content-full">
                    <h2 class="h4 push-10">Lost in the mountains</h2>
                    <p class="text-gray-dark">Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum..</p>
                    <a class="btn btn-sm btn-default" href="javascript:void(0)">Read More</a>
                </div>
            </div>
            <!-- END Article -->

            <!-- Course -->
            <a class="block block-link-hover3" href="javascript:void(0)">
                <div class="block-content block-content-full text-center bg-amethyst ribbon ribbon-bookmark ribbon-crystal">
                    <div class="ribbon-box font-w600">$5</div>
                    <div class="item item-2x item-circle bg-crystal-op push-20-t push-20">
                        <i class="si si-vector text-white-op"></i>
                    </div>
                    <div class="text-white-op">
                        <em>4 lessons</em> &bull; <em>2 hours</em>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <h4 class="mheight-100">Using SVG is easier than ever</h4>
                    <div class="font-s12">May 12, 2016</div>
                </div>
            </a>
            <!-- END Course -->

            <!-- FAQ -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button"><i class="si si-question"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">FAQ</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Slick slider (.js-slider class is initialized in App() -> uiHelperSlick()) -->
                    <!-- For more info and examples you can check out http://kenwheeler.github.io/slick/ -->
                    <div class="js-slider slick-nav-white slick-nav-hover" data-slider-dots="true">
                        <div>
                            <div class="push-30-t">
                                <span class="item item-2x item-circle bg-gray-lighter">
                                    <i class="si si-call-in text-black"></i>
                                </span>
                            </div>
                            <h3 class="font-w300 text-black push-30-t push-30">Do I have access to phone support?</h3>
                            <div class="mheight-150">
                                <p class="text-gray-dark">Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum,</p>
                            </div>
                        </div>
                        <div>
                            <div class="push-30-t">
                                <span class="item item-2x item-circle bg-gray-lighter">
                                    <i class="si si-calendar text-black"></i>
                                </span>
                            </div>
                            <h3 class="font-w300 text-black push-30-t push-30">How can I manage my events?</h3>
                            <div class="mheight-150">
                                <p class="text-gray-dark">Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum,</p>
                            </div>
                        </div>
                        <div>
                            <div class="push-30-t">
                                <span class="item item-2x item-circle bg-gray-lighter">
                                    <i class="si si-speedometer text-black"></i>
                                </span>
                            </div>
                            <h3 class="font-w300 text-black push-30-t push-30">What if I need more power?</h3>
                            <div class="mheight-150">
                                <p class="text-gray-dark">Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum,</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FAQ -->

            <!-- New Episode -->
            <div class="block">
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-gray-lighter">
                        <i class="si si-film text-black"></i>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    A new episode of <a class="font-w600" href="javascript:void(0)">Game of Thrones</a> is available.
                </div>
            </div>
            <!-- END New Episode -->
        </div>
        <div class="col-lg-4">
            <!-- Friend +1 -->
            <div class="block">
                <div class="block-content block-content-full">
                    <a class="font-w600" href="javascript:void(0)"><?php $one->get_name(); ?></a> accepted your friend request.
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="item item-2x item-circle bg-gray-lighter">
                        <i class="si si-user-follow text-black"></i>
                    </div>
                </div>
            </div>
            <!-- END Friend +1 -->

            <!-- Category -->
            <div class="bg-image" style="background-image: url('assets/img/photos/photo25.jpg');">
                <div class="bg-black-op">
                    <div class="block block-themed block-transparent">
                        <div class="block-header">
                            <ul class="block-options">
                                <li>
                                    <button type="button"><i class="si si-settings"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Category</h3>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <a class="h1 font-w300 text-white" href="javascript:void(0)">Photography</a>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <span class="text-white-op"><em>Updated 3 hours ago</em></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Category -->

            <!-- Article -->
            <div class="block">
                <div class="block-content block-content-full">
                    <a class="font-w600" href="javascript:void(0)"><?php $one->get_name(); ?></a> just posted an article.
                </div>
                <?php $one->get_photo(8, false, 'img-responsive'); ?>
                <div class="block-content block-content-full">
                    <h3 class="h4 push-10">A day in the city</h3>
                    <p class="text-gray-dark">Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum..</p>
                    <a class="btn btn-sm btn-default" href="javascript:void(0)">Read More</a>
                </div>
            </div>
            <!-- END Article -->

            <!-- Add Friend -->
            <div class="bg-image" style="background-image: url('assets/img/photos/photo21.jpg');">
                <div class="bg-black-op">
                    <div class="block block-themed block-transparent">
                        <div class="block-header">
                            <h3 class="block-title text-center">Photographer</h3>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <div class="push">
                                <?php $one->get_avatar(1, false, 96, true); ?>
                            </div>
                            <h3 class="h1 font-w300 text-white"><?php $one->get_name('female'); ?></h3>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <a class="btn btn-sm btn-default" href="javascript:void(0)">
                                <i class="fa fa-fw fa-plus"></i> Add friend
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Add Friend -->

            <!-- Story -->
            <a class="block block-link-hover3" href="javascript:void(0)">
                <?php $one->get_photo(23, false, 'img-responsive'); ?>
                <div class="block-content">
                    <div class="push">
                        <em class="pull-right">12 min</em>
                        <span class="text-primary font-w600"><?php $one->get_name(); ?></span> on July 6, 2015
                    </div>
                    <h4 class="push-10">Travel &amp; Work</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ultrices, justo vel imperdiet gravida...</p>
                </div>
            </a>
            <!-- END Story -->
        </div>
        <div class="col-lg-4">
            <!-- Weather -->
            <div class="block">
                <div class="bg-image" style="background-image: url('assets/img/photos/photo2.jpg');">
                    <div class="bg-black-op">
                        <div class="block-content block-content-full text-center">
                            <i class="fa fa-4x fa-sun-o text-warning push-30-t"></i>
                            <h3 class="h4 text-uppercase text-white push-30-t push-5">Chania, Crete</h3>
                            <h4 class="h5 text-white-op push-20">Greece</h4>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row text-center">
                        <div class="col-xs-4">
                            <div class="h2 font-w300">28&deg;C</div>
                            <div class="h5 text-muted push-5-t">MON</div>
                        </div>
                        <div class="col-xs-4">
                            <div class="h2 font-w300">30&deg;C</div>
                            <div class="h5 text-muted push-5-t">TUE</div>
                        </div>
                        <div class="col-xs-4">
                            <div class="h2 font-w300">32&deg;C</div>
                            <div class="h5 text-muted push-5-t">WED</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Weather -->

            <!-- Special Offer -->
            <div class="block">
                <div class="block-content block-content-full text-center">
                    <h3 class="font-w300 text-black push-30-t push-30">Special offer only for today with all packages 50% off.</h3>
                    <div class="push-30">
                        <span class="item item-2x item-circle bg-success-light">
                            <i class="si si-badge text-success"></i>
                        </span>
                    </div>
                    <a class="btn btn-minw btn-rounded btn-noborder btn-success push-5" href="javascript:void(0)">Upgrade</a>
                    <p class="font-s12 text-muted">Only for limited accounts.</p>
                </div>
            </div>
            <!-- END Special Offer -->

            <!-- Gift Card -->
            <div class="block block-rounded">
                <div class="block-content block-content-full bg-city text-center">
                    <div class="push-10-t push-10">
                        <i class="fa fa-4x fa-apple text-white-op push-10"></i>
                        <h3 class="h4 text-white">Gift Card</h3>
                    </div>
                </div>
                <div class="block-content block-content-full clearfix">
                    <div class="pull-right">
                        <a class="btn btn-sm btn-default" href="javascript:void(0)">Buy Now!</a>
                    </div>
                    <div class="pull-left">
                        <div class="h3">$50,00</div>
                    </div>
                </div>
            </div>
            <!-- END Gift Card -->

            <!-- Product -->
            <div class="block">
                <div class="block-content block-content-full">
                    <a class="font-w600" href="javascript:void(0)"><?php $one->get_name(); ?></a> added a new product.
                </div>
                <div class="img-container">
                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product1.png" alt="">
                    <div class="img-options">
                        <div class="img-options-content">
                            <div class="push-20">
                                <a class="btn btn-sm btn-default" href="javascript:void(0)">View</a>
                                <a class="btn btn-sm btn-default" href="javascript:void(0)">
                                    <i class="fa fa-plus"></i> Add to cart
                                </a>
                            </div>
                            <div class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half-full"></i>
                                <span class="text-white">(35)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <div class="push-10">
                        <div class="h4 font-w600 text-success pull-right push-10-l">$9</div>
                        <a class="h4" href="javascript:void(0)">Iconic</a>
                    </div>
                    <p class="text-gray-dark">Beautifully crafted icon set</p>
                </div>
            </div>
            <!-- END Product -->

            <!-- New Photos -->
            <div class="block">
                <div class="block-content">
                    <a class="font-w600" href="javascript:void(0)"><?php $one->get_name(); ?></a> added 6 new photos.
                </div>
                <div class="block-content block-content-full content-grid">
                    <!-- Gallery (.js-gallery class is initialized in App() -> uiHelperMagnific()) -->
                    <!-- For more info and examples you can check out http://dimsemenov.com/plugins/magnific-popup/ -->
                    <div class="row js-gallery">
                        <?php for($i = 19; $i < 25; $i++) { ?>
                        <div class="col-xs-4">
                            <a class="img-link" href="<?php echo $one->assets_folder; ?>/img/photos/photo<?php echo $i; ?>@2x.jpg">
                                <?php $one->get_photo($i, false, 'img-responsive'); ?>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- END New Photos -->
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/slick/slick.min.js"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/magnific-popup/magnific-popup.min.js"></script>

<!-- Page JS Code -->
<script>
    jQuery(function(){
        // Init page helpers (Slick Slider + Magnific Popup plugins)
        App.initHelpers(['slick', 'magnific-popup']);
    });
</script>

<?php require 'inc/views/template_footer_end.php'; ?>