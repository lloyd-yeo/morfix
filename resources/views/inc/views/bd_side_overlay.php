<?php
/**
 * bd_side_overlay.php
 *
 * Author: pixelcave
 *
 * The side overlay of each page (Boxed Dashboard)
 *
 */
?>
<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Overlay Scroll Container -->
    <div id="side-overlay-scroll">
        <!-- Side Header -->
        <div class="side-header side-content">
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
            <button class="btn btn-default pull-right" type="button" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-times"></i>
            </button>
            <span class="font-w600">John Parker</span>
        </div>
        <!-- END Side Header -->

        <!-- Side Content -->
        <div class="side-content remove-padding-t">
            <!-- Account -->
            <div class="block pull-r-l">
                <div class="block-header bg-gray-lighter">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Account</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal" action="bd_dashboard.php" method="post" onsubmit="return false;">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Username</label>
                                <div class="form-control-static font-w700">johnpar</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="bd-qsettings-name">Name</label>
                                <input class="form-control" type="text" id="bd-qsettings-name" name="bd-qsettings-name" placeholder="Enter your name.." value="John Parker">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="bd-qsettings-email">Email</label>
                                <input class="form-control" type="email" id="bd-qsettings-email" name="bd-qsettings-email" placeholder="Enter your email.." value="john.parker@example.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="bd-qsettings-password">New Password</label>
                                <input class="form-control" type="password" id="bd-qsettings-password" name="bd-qsettings-new-password" placeholder="Enter a new password..">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="bd-qsettings-password2">Confirm New Password</label>
                                <input class="form-control" type="password" id="bd-qsettings-password2" name="bd-qsettings-new-password2" placeholder="Confirm your new password..">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-minw btn-rounded btn-primary" type="submit">
                                    <i class="fa fa-check push-5-r"></i>Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Account -->

            <!-- Quick Settings -->
            <div class="block pull-r-l">
                <div class="block-header bg-gray-lighter">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Quick Settings</h3>
                </div>
                <div class="block-content">
                    <!-- Quick Settings Form -->
                    <form class="form-bordered" action="base_pages_dashboard.php" method="post" onsubmit="return false;">
                        <div class="form-group">
                            <div class="row">
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
                        </div>
                        <div class="form-group">
                            <div class="row">
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
                        </div>
                        <div class="form-group">
                            <div class="row">
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
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="font-s13 font-w600">API Access</div>
                                    <div class="font-s13 font-w400 text-muted">Enable/Disable access</div>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <label class="css-input switch switch-sm switch-primary push-10-t">
                                        <input type="checkbox"><span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END Quick Settings Form -->
                </div>
            </div>
            <!-- END Quick Settings -->
        </div>
        <!-- END Side Content -->
    </div>
    <!-- END Side Overlay Scroll Container -->
</aside>
<!-- END Side Overlay -->
