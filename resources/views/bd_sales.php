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
            <li>
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
            <li class="active">
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
    <div class="bg-image img-rounded overflow-hidden push" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo37@2x.jpg');">
        <div class="bg-black-op">
            <div class="content">
                <div class="block block-transparent block-themed text-center">
                    <div class="block-content">
                        <h1 class="h1 font-w700 text-white animated fadeInDown push-5">Sales</h1>
                        <h2 class="h4 font-w400 text-white-op animated fadeInUp">You are doing great, 150 new sales today!</h2>
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
                    <div class="font-s12 font-w700">Today</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">150</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">Last Week</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">900</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">Last Month</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">2500</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">All</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">63000</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Stats -->

    <!-- Sales -->
    <div class="block">
        <div class="block-header">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                </li>
            </ul>
            <h3 class="block-title">Sales</h3>
        </div>
        <div class="block-content block-content-full bg-gray-lighter text-center">
            <div class="btn-group">
                <button class="btn btn-sm btn-default" type="submit">Last 30 days</button>
                <button class="btn btn-sm btn-default" type="submit">Last 6 months</button>
                <button class="btn btn-sm btn-default" type="submit">Last year</button>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter">
                    <tbody>
                        <tr>
                            <td class="text-center text-muted" style="width: 200px;">September 29, 2016</td>
                            <td style="width: 100px;">#ID59630</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Calendious</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right" style="width: 80px;">
                                <span class="font-w600 text-success">+ $44</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59629</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Calendious</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('female'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $44</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59628</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Super Badges Pack</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $58</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59627</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Todo App</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $46</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59626</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Mailday</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $52</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59625</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Super Badges Pack</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('female'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $58</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59624</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Super Badges Pack</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $58</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59623</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">Super Badges Pack</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $58</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 29, 2016</td>
                            <td>#ID59622</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">e-Music</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $50</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-muted">September 28, 2016</td>
                            <td>#ID59621</td>
                            <td>
                                <a class="font-w600" href="javascript:void(0)">e-Music</a>
                            </td>
                            <td class="text-right">
                                <a class="font-w600" href="javascript:void(0)"><?php echo $one->get_name('male'); ?></a>
                            </td>
                            <td class="text-right">
                                <span class="font-w600 text-success">+ $50</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center push">
                <button class="btn btn-xs btn-default" type="button">
                    <i class="fa fa-arrow-down push-5-r text-primary"></i>Load More..
                </button>
            </div>
        </div>
    </div>
    <!-- END Sales -->
</div>
<!-- END Page Content -->

<?php require 'inc/views/bd_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>