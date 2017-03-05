<?php require 'inc/config.php'; ?>
<?php
// Specific Page Options
$one->body_bg = $one->assets_folder . '/img/photos/photo24@2x.jpg';
?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>

<!-- Coming Soon Content -->
<div class="content content-boxed overflow-hidden animated fadeIn">
    <div class="row text-center push">
        <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
            <div class="bg-black-op">
                <div class="block block-transparent">
                    <div class="block-content block-content-full bg-black-op">
                        <h1 class="text-white push-20-t push-5"><i class="fa fa-circle-o-notch text-warning"></i></h1>
                        <h2 class="h4 text-gray push-20">Stay tuned! We are working on it and it is coming soon!</h2>
                    </div>
                    <div class="block-content block-content-full">
                        <!-- Countdown.js (class is initialized in js/pages/base_pages_coming_soon.js), for more examples you can check out https://github.com/hilios/jQuery.countdown -->
                        <div class="js-countdown"></div>
                    </div>
                    <div class="block-content block-content-full bg-black-op">
                        <a class="btn btn-warning btn-rounded btn-noborder" href="base_pages_blank.php">
                            <i class="fa fa-arrow-left push-5-r"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Coming Soon Content -->

<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/jquery-countdown/jquery.countdown.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_pages_coming_soon_v2.js"></script>

<?php require 'inc/views/template_footer_end.php'; ?>