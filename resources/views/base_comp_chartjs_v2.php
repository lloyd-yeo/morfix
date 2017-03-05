<?php require 'inc/config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                Chart.js v2 <small>Simple yet flexible JavaScript charting for designers &amp; developers</small>
            </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li>Components</li>
                <li><a class="link-effect" href="">Chart.js v2</a></li>
            </ol>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <!-- Chart.js v2 Charts (initialized in js/pages/base_comp_chartjs_v2.js), for more examples you can check out http://www.chartjs.org/docs/ -->
    <div class="row">
        <div class="col-lg-6">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Lines</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Lines Chart Container -->
                    <canvas class="js-chartjs2-lines"></canvas>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
        <div class="col-lg-6">
            <!-- Bars Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Bars</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Bars Chart Container -->
                    <canvas class="js-chartjs2-bars"></canvas>
                </div>
            </div>
            <!-- END Bars Chart -->
        </div>
        <div class="col-lg-6">
            <!-- Radar Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Radar</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Radar Chart Container -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <canvas class="js-chartjs2-radar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Radar Chart -->
        </div>
        <div class="col-lg-6">
            <!-- Polar Area Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Polar Area</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Polar Area Chart Container -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <canvas class="js-chartjs2-polar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Polar Area Chart -->
        </div>
        <div class="col-lg-6">
            <!-- Pie Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Pie</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Pie Chart Container -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <canvas class="js-chartjs2-pie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Pie Chart -->
        </div>
        <div class="col-lg-6">
            <!-- Donut Chart -->
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Donut</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Donut Chart Container -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <canvas class="js-chartjs2-donut"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Donut Chart -->
        </div>
    </div>
    <!-- END Chart.js v2 Charts -->
</div>
<!-- END Page Content -->

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/chartjsv2/Chart.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_comp_chartjs_v2.js"></script>

<?php require 'inc/views/template_footer_end.php'; ?>