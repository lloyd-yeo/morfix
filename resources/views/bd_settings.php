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
            <li>
                <a href="bd_sales.php">
                    <i class="fa fa-paypal push-5-r"></i>Sales
                </a>
            </li>
            <li class="active">
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
    <div class="bg-image img-rounded overflow-hidden push" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo28@2x.jpg');">
        <div class="bg-black-op">
            <div class="content">
                <div class="block block-transparent block-themed text-center">
                    <div class="block-content">
                        <h1 class="h1 font-w700 text-white animated fadeInDown push-5">Settings</h1>
                        <h2 class="h4 font-w400 text-white-op animated fadeInUp">Manage your account.</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Section -->

    <!-- Settings -->
    <form action="bd_settings.php" method="post" onsubmit="return false;">
        <div class="block">
            <ul class="nav nav-tabs nav-justified push-20" data-toggle="tabs">
                <li class="active">
                    <a href="#tab-bd-settings-general"><i class="fa fa-fw fa-pencil"></i> General</a>
                </li>
                <li>
                    <a href="#tab-bd-settings-password"><i class="fa fa-fw fa-asterisk"></i> Password</a>
                </li>
                <li>
                    <a href="#tab-bd-settings-privacy"><i class="fa fa-fw fa-lock"></i> Privacy</a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <!-- General Tab -->
                <div class="tab-pane fade in active" id="tab-bd-settings-general">
                    <div class="row items-push">
                        <div class="col-sm-6 col-sm-offset-3 form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label>Username</label>
                                    <div class="form-control-static font-w700">johnpar</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-email">Email Address</label>
                                    <input class="form-control input-lg" type="email" id="bd-settings-email" name="bd-settings-email" placeholder="Enter your email.." value="john.parker@example.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-name">Name</label>
                                    <input class="form-control input-lg" type="text" id="bd-settings-name" name="bd-settings-name" placeholder="Enter your name.." value="John Parker">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-bio">Bio</label>
                                    <textarea class="form-control input-lg" id="bd-settings-bio" name="bd-settings-bio" rows="15" placeholder="Enter a few details about yourself..">Hi there, we are passionate people who love to create awesome products that will make your life easier! Check them out and let us know what do you think!</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-skills">Skills</label>
                                    <select class="form-control" id="bd-settings-skills" name="bd-settings-skills" size="8" multiple="">
                                        <option value="1" selected>HTML</option>
                                        <option value="2" selected>CSS</option>
                                        <option value="3" selected>JavaScript</option>
                                        <option value="4">PHP</option>
                                        <option value="5">Ruby</option>
                                        <option value="6" selected>Photoshop</option>
                                        <option value="6" selected>Sketch</option>
                                        <option value="7">Illustrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label for="bd-settings-city">Where do you live?</label>
                                    <input class="form-control input-lg" type="text" id="bd-settings-city" name="bd-settings-city" placeholder="Enter your location..">
                                </div>
                                <div class="col-sm-6">
                                    <label for="bd-settings-age">Age</label>
                                    <input class="form-control input-lg" type="text" id="bd-settings-age" name="bd-settings-age" placeholder="Enter your age..">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12">Gender</label>
                                <div class="col-xs-12">
                                    <label class="css-input css-radio css-radio-primary push-10-r">
                                        <input type="radio" name="bd-settings-gender-group"><span></span> Female
                                    </label>
                                    <label class="css-input css-radio css-radio-primary">
                                        <input type="radio" name="bd-settings-gender-group" checked><span></span> Male
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END General Tab -->

                <!-- Password Tab -->
                <div class="tab-pane fade" id="tab-bd-settings-password">
                    <div class="row items-push">
                        <div class="col-sm-6 col-sm-offset-3 form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-password">Current Password</label>
                                    <input class="form-control input-lg" type="password" id="bd-settings-password" name="bd-settings-password">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-password-new">New Password</label>
                                    <input class="form-control input-lg" type="password" id="bd-settings-password-new" name="bd-settings-password-new">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-settings-password-new-confirm">Confirm New Password</label>
                                    <input class="form-control input-lg" type="password" id="bd-settings-password-new-confirm" name="bd-settings-password-new-confirm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Password Tab -->

                <!-- Privacy Tab -->
                <div class="tab-pane fade" id="tab-bd-settings-privacy">
                    <div class="row items-push">
                        <div class="col-sm-6 col-sm-offset-3 form-horizontal">
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <div class="font-s13 font-w600">Online Status</div>
                                    <div class="font-s13 font-w400 text-muted">Show your status to all</div>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <label class="css-input switch switch-sm switch-primary push-10-t">
                                        <input type="checkbox" checked><span></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <div class="font-s13 font-w600">Auto Updates</div>
                                    <div class="font-s13 font-w400 text-muted">Keep up to date</div>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <label class="css-input switch switch-sm switch-primary push-10-t">
                                        <input type="checkbox" checked><span></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <div class="font-s13 font-w600">Notifications</div>
                                    <div class="font-s13 font-w400 text-muted">Do you need them?</div>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <label class="css-input switch switch-sm switch-primary push-10-t">
                                        <input type="checkbox"><span></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <div class="font-s13 font-w600">API Access</div>
                                    <div class="font-s13 font-w400 text-muted">Enable/Disable access</div>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <label class="css-input switch switch-sm switch-primary push-10-t">
                                        <input type="checkbox"><span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Privacy Tab -->
            </div>
            <div class="block-content block-content-full bg-gray-lighter text-center">
                <button class="btn btn-noborder btn-minw btn-rounded btn-primary" type="submit">
                    <i class="fa fa-check push-5-r"></i>Update
                </button>
                <button class="btn btn-noborder btn-minw btn-rounded btn-warning" type="reset">
                    <i class="fa fa-refresh push-5-r"></i>Reset
                </button>
            </div>
        </div>
    </form>
    <!-- END Main Content -->
</div>
<!-- END Page Content -->

<?php require 'inc/views/bd_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>
<?php require 'inc/views/template_footer_end.php'; ?>