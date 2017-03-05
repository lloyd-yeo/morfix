<?php require 'inc/config.php'; ?>
<?php require 'inc/bd_config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Sub Header -->
<div class="bg-gray-lighter visible-xs">
    <div class="content-mini content-boxed">
        <button class="btn btn-block btn-default visible-xs push" data-toggle="collapse" data-target="#sub-header-nav">
            <i class="fa fa-navicon push-5-r"></i>Menu
        </button>
    </div>
</div>
<div class="bg-primary-lighter collapse navbar-collapse remove-padding" id="sub-header-nav">
    <div class="content-mini content-boxed">
        <ul class="nav nav-pills nav-sub-header push">
            <li class="active">
                <a href="bd_dashboard.php">
                    <i class="fa fa-dashboard push-5-r"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="bd_products.php">
                    <i class="fa fa-briefcase push-5-r"></i>Products
                </a>
            </li>
            <li>
                <a href="bd_customers.php">
                    <i class="fa fa-users push-5-r"></i>Customers
                </a>
            </li>
            <li>
                <a href="bd_sales.php">
                    <i class="fa fa-paypal push-5-r"></i>Sales
                </a>
            </li>
            <li>
                <a href="bd_settings.php">
                    <i class="fa fa-cog push-5-r"></i>Settings
                </a>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-coffee push-5-r"></i>More <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)">Payment</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Privacy</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="javascript:void(0)">Shop</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- END Sub Header -->

<!-- Page Content -->
<div class="content content-boxed">
    <!-- Section -->
    <div class="bg-image img-rounded overflow-hidden push" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo29@2x.jpg');">
        <div class="bg-black-op">
            <div class="content">
                <div class="block block-transparent block-themed text-center">
                    <div class="block-content">
                        <h1 class="h1 font-w700 text-white animated fadeInDown push-5">Dashboard</h1>
                        <h2 class="h4 font-w400 text-white-op animated fadeInUp">Welcome Administrator.</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Section -->

    <!-- Stats -->
    <div class="row text-uppercase">
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-muted">
                        <small><i class="si si-calendar"></i> Today</small>
                    </div>
                    <div class="font-s12 font-w700">Unq Visitors</div>
                    <a class="h2 font-w300 text-primary" href="bd_dashboard.php" data-toggle="countTo" data-to="96780"></a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-muted">
                        <small><i class="si si-calendar"></i> Today</small>
                    </div>
                    <div class="font-s12 font-w700">Prd Sales</div>
                    <a class="h2 font-w300 text-primary" href="bd_dashboard.php" data-toggle="countTo" data-to="680"></a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-muted">
                        <small><i class="si si-calendar"></i> Today</small>
                    </div>
                    <div class="font-s12 font-w700">Earnings</div>
                    <a class="h2 font-w300 text-primary" href="bd_dashboard.php" data-toggle="countTo" data-to="63000" data-before="$ "></a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-muted">
                        <small><i class="si si-calendar"></i> Today</small>
                    </div>
                    <div class="font-s12 font-w700">Average Sale</div>
                    <a class="h2 font-w300 text-primary" href="bd_dashboard.php" data-toggle="countTo" data-to="249" data-before="$ "></a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Stats -->

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="block block-rounded block-opt-refresh-icon8">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Earnings in $</h3>
                </div>
                <div class="block-content block-content-full bg-gray-lighter text-center">
                    <!-- Chart.js Charts (initialized in js/pages/base_pages_dashboard_v2.js), for more examples you can check out http://www.chartjs.org/docs/ -->
                    <div style="height: 320px;"><canvas class="js-dash-chartjs-earnings"></canvas></div>
                </div>
                <div class="block-content text-center">
                    <div class="row items-push-2x text-center push-20-t">
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-bank fa-2x"></i></div>
                            <div class="h5 text-muted">$148,000</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-angle-double-up fa-2x"></i></div>
                            <div class="h5 text-muted">+9% Earnings</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-headphones fa-2x"></i></div>
                            <div class="h5 text-muted">+20% Tickets</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-users fa-2x"></i></div>
                            <div class="h5 text-muted">+46% Clients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="block block-rounded block-opt-refresh-icon8">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Sales</h3>
                </div>
                <div class="block-content block-content-full bg-gray-lighter text-center">
                    <!-- Chart.js Charts (initialized in js/pages/base_pages_dashboard_v2.js), for more examples you can check out http://www.chartjs.org/docs/ -->
                    <div style="height: 320px;"><canvas class="js-dash-chartjs-sales"></canvas></div>
                </div>
                <div class="block-content text-center">
                    <div class="row items-push-2x text-center push-20-t">
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-wordpress fa-2x"></i></div>
                            <div class="h5 text-muted">+20% Themes</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-font fa-2x"></i></div>
                            <div class="h5 text-muted">+25% Fonts</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-archive fa-2x"></i></div>
                            <div class="h5 text-muted">-10% Icons</div>
                        </div>
                        <div class="col-xs-6 col-lg-3">
                            <div class="push-15"><i class="fa fa-paint-brush fa-2x"></i></div>
                            <div class="h5 text-muted">+8% Graphics</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Charts -->
</div>
<!-- END Page Content -->

<?php require 'inc/views/bd_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/chartjs/Chart.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_pages_dashboard_v2.js"></script>
<script>
    jQuery(function(){
        // Init page helpers (CountTo plugin)
        App.initHelpers('appear-countTo');
    });
</script>

<?php require 'inc/views/template_footer_end.php'; ?>