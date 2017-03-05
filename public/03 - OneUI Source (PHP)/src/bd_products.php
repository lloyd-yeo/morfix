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
            <li class="active">
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
    <div class="bg-image img-rounded overflow-hidden push" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo25@2x.jpg');">
        <div class="bg-black-op">
            <div class="content">
                <div class="block block-transparent block-themed text-center">
                    <div class="block-content">
                        <h1 class="h1 font-w700 text-white animated fadeInDown push-5">Products</h1>
                        <h2 class="h4 font-w400 text-white-op animated fadeInUp">There are currently 8 products in your portfolio.</h2>
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
                    <div class="font-s12 font-w700">Products</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">8</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">Tickets</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">390</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">Purchases</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">63000</a>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="font-s12 font-w700">Earnings</div>
                    <a class="h2 font-w300 text-primary" href="javascript:void(0)">$ 980600</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END Stats -->

    <!-- Products -->
    <div class="block block-rounded">
        <div class="block-header">
            <ul class="block-options">
                <li>
                    <button type="button"><i class="fa fa-plus"></i> Add new</button>
                </li>
                <li>
                    <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                </li>
            </ul>
            <h3 class="block-title">Products</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter">
                    <tbody>
                        <tr>
                            <td class="text-center" style="width: 200px;">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product10.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Calendious</h4>
                                <p class="remove-margin-b">Management for freelancers</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">7850</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$345.400</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$44</span>
                                <p class="remove-margin-b">7850 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">998 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product7.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>RPG Game Pack</h4>
                                <p class="remove-margin-b">10-in-1 Anniversary Pack</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">8200</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$393.600</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$48</span>
                                <p class="remove-margin-b">8200 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">843 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product12.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>e-Music</h4>
                                <p class="remove-margin-b">Music streaming service</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">9337</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$466.850</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$50</span>
                                <p class="remove-margin-b">9337 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">859 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product11.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Todo App</h4>
                                <p class="remove-margin-b">All your tasks in one place</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">6950</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$319.700</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$46</span>
                                <p class="remove-margin-b">6950 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">1020 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product8.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Antivir</h4>
                                <p class="remove-margin-b">Antivirus protection for all</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">6560</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$249.280</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$38</span>
                                <p class="remove-margin-b">6560 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">760 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product2.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Mailday</h4>
                                <p class="remove-margin-b">Pro email templates</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">9220</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$479.440</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$52</span>
                                <p class="remove-margin-b">9220 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">669 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product4.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Steam Games</h4>
                                <p class="remove-margin-b">3-in-1 Adventure Pack</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">7560</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$400.680</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$53</span>
                                <p class="remove-margin-b">7560 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">865 Ratings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <div style="width: 180px;">
                                    <img class="img-responsive" src="<?php echo $one->assets_folder; ?>/img/various/ecom_product6.png" alt="">
                                </div>
                            </td>
                            <td>
                                <h4>Super Badges Pack</h4>
                                <p class="remove-margin-b">1000s of high quality badges</p>
                                <a class="font-w600" href="javascript:void(0)">design_agency</a>
                            </td>
                            <td>
                                <p class="remove-margin-b">Purchases: <span class="text-gray-dark">8980</span></p>
                                <p>Item Sales: <span class="text-gray-dark">$520.840</span></p>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-download push-5-r text-primary"></i>Download
                                </button>
                                <button class="btn btn-xs btn-default" type="button">
                                    <i class="fa fa-pencil push-5-r text-success"></i>Edit
                                </button>
                            </td>
                            <td class="text-center">
                                <span class="h1 font-w700 text-success">$58</span>
                                <p class="remove-margin-b">8980 purchases</p>
                                <span class="text-warning">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <p class="remove-margin-b font-s12">798 Ratings</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Products -->
</div>
<!-- END Page Content -->

<?php require 'inc/views/bd_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>
