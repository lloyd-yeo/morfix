<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>OneUI - Admin Dashboard Template &amp; UI Framework</title>

        <meta name="description" content="OneUI - Admin Dashboard Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="assets/img/favicons/favicon.png">

        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="assets/img/favicons/favicon-192x192.png" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" id="css-main" href="assets/css/oneui.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <!--
            Available Classes:

            'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

            'sidebar-l'                  Left Sidebar and right Side Overlay
            'sidebar-r'                  Right Sidebar and left Side Overlay
            'sidebar-mini'               Mini hoverable Sidebar (> 991px)
            'sidebar-o'                  Visible Sidebar by default (> 991px)
            'sidebar-o-xs'               Visible Sidebar by default (< 992px)

            'side-overlay-hover'         Hoverable Side Overlay (> 991px)
            'side-overlay-o'             Visible Side Overlay by default (> 991px)

            'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

            'header-navbar-fixed'        Enables fixed header
        -->
        <div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
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
                        <span>
                            <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar10.jpg" alt="">
                            <span class="font-w600 push-10-l">Jack Greene</span>
                        </span>
                    </div>
                    <!-- END Side Header -->

                    <!-- Side Content -->
                    <div class="side-content remove-padding-t">
                        <!-- Side Overlay Tabs -->
                        <div class="block pull-r-l border-t">
                            <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
                                <li class="active">
                                    <a href="#tabs-side-overlay-overview"><i class="fa fa-fw fa-coffee"></i> Overview</a>
                                </li>
                                <li>
                                    <a href="#tabs-side-overlay-sales"><i class="fa fa-fw fa-line-chart"></i> Sales</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade fade-right in active" id="tabs-side-overlay-overview">
                                    <!-- Activity -->
                                    <div class="block pull-r-l">
                                        <div class="block-header bg-gray-lighter">
                                            <ul class="block-options">
                                                <li>
                                                    <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                                </li>
                                                <li>
                                                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                                </li>
                                            </ul>
                                            <h3 class="block-title">Recent Activity</h3>
                                        </div>
                                        <div class="block-content">
                                            <!-- Activity List -->
                                            <ul class="list list-activity">
                                                <li>
                                                    <i class="si si-wallet text-success"></i>
                                                    <div class="font-w600">New sale ($15)</div>
                                                    <div><a href="javascript:void(0)">Admin Template</a></div>
                                                    <div><small class="text-muted">3 min ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="si si-pencil text-info"></i>
                                                    <div class="font-w600">You edited the file</div>
                                                    <div><a href="javascript:void(0)"><i class="fa fa-file-text-o"></i> Documentation.doc</a></div>
                                                    <div><small class="text-muted">15 min ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="si si-close text-danger"></i>
                                                    <div class="font-w600">Project deleted</div>
                                                    <div><a href="javascript:void(0)">Line Icon Set</a></div>
                                                    <div><small class="text-muted">4 hours ago</small></div>
                                                </li>
                                            </ul>
                                            <!-- END Activity List -->
                                        </div>
                                    </div>
                                    <!-- END Activity -->

                                    <!-- Online Friends -->
                                    <div class="block pull-r-l">
                                        <div class="block-header bg-gray-lighter">
                                            <ul class="block-options">
                                                <li>
                                                    <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                                </li>
                                                <li>
                                                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                                </li>
                                            </ul>
                                            <h3 class="block-title">Online Friends</h3>
                                        </div>
                                        <div class="block-content block-content-full">
                                            <!-- Users Navigation -->
                                            <ul class="nav-users remove-margin-b">
                                                <li>
                                                    <a href="base_pages_profile.html">
                                                        <img class="img-avatar" src="assets/img/avatars/avatar8.jpg" alt="">
                                                        <i class="fa fa-circle text-success"></i> Evelyn Willis
                                                        <div class="font-w400 text-muted"><small>Copywriter</small></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="base_pages_profile.html">
                                                        <img class="img-avatar" src="assets/img/avatars/avatar14.jpg" alt="">
                                                        <i class="fa fa-circle text-success"></i> Keith Simpson
                                                        <div class="font-w400 text-muted"><small>Web Developer</small></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="base_pages_profile.html">
                                                        <img class="img-avatar" src="assets/img/avatars/avatar5.jpg" alt="">
                                                        <i class="fa fa-circle text-success"></i> Ann Parker
                                                        <div class="font-w400 text-muted"><small>Web Designer</small></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="base_pages_profile.html">
                                                        <img class="img-avatar" src="assets/img/avatars/avatar6.jpg" alt="">
                                                        <i class="fa fa-circle text-warning"></i> Amber Walker
                                                        <div class="font-w400 text-muted"><small>Photographer</small></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="base_pages_profile.html">
                                                        <img class="img-avatar" src="assets/img/avatars/avatar13.jpg" alt="">
                                                        <i class="fa fa-circle text-warning"></i> Scott Ruiz
                                                        <div class="font-w400 text-muted"><small>Graphic Designer</small></div>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- END Users Navigation -->
                                        </div>
                                    </div>
                                    <!-- END Online Friends -->

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
                                            <form class="form-bordered" action="base_pages_dashboard.html" method="post" onsubmit="return false;">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="font-s13 font-w600">Online Status</div>
                                                            <div class="font-s13 font-w400 text-muted">Show your status to all</div>
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
                                                            <div class="font-s13 font-w600">Auto Updates</div>
                                                            <div class="font-s13 font-w400 text-muted">Keep up to date</div>
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
                                                            <div class="font-s13 font-w600">Notifications</div>
                                                            <div class="font-s13 font-w400 text-muted">Do you need them?</div>
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
                                                            <div class="font-s13 font-w600">API Access</div>
                                                            <div class="font-s13 font-w400 text-muted">Enable/Disable access</div>
                                                        </div>
                                                        <div class="col-xs-4 text-right">
                                                            <label class="css-input switch switch-sm switch-primary push-10-t">
                                                                <input type="checkbox" checked><span></span>
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
                                <!-- END Overview Tab -->

                                <!-- Sales Tab -->
                                <div class="tab-pane fade fade-left" id="tabs-side-overlay-sales">
                                    <div class="block pull-r-l">
                                        <!-- Stats -->
                                        <div class="block-content pull-t">
                                            <div class="row items-push">
                                                <div class="col-xs-6">
                                                    <div class="font-w700 text-gray-darker text-uppercase">Sales</div>
                                                    <a class="h3 font-w300 text-primary" href="javascript:void(0)">22030</a>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="font-w700 text-gray-darker text-uppercase">Balance</div>
                                                    <a class="h3 font-w300 text-primary" href="javascript:void(0)">$ 4.589,00</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Stats -->

                                        <!-- Today -->
                                        <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="font-w600 font-s13 text-gray-darker text-uppercase">Today</span>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <span class="font-s13 text-muted">$996</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <ul class="list list-activity pull-r-l">
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $249</div>
                                                    <div><small class="text-muted">3 min ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $129</div>
                                                    <div><small class="text-muted">50 min ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $119</div>
                                                    <div><small class="text-muted">2 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $499</div>
                                                    <div><small class="text-muted">3 hours ago</small></div>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- END Today -->

                                        <!-- Yesterday -->
                                        <div class="block-content block-content-full block-content-mini bg-gray-lighter">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <span class="font-w600 font-s13 text-gray-darker text-uppercase">Yesterday</span>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <span class="font-s13 text-muted">$765</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <ul class="list list-activity pull-r-l">
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $249</div>
                                                    <div><small class="text-muted">26 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-danger"></i>
                                                    <div class="font-w600">Product Purchase - $50</div>
                                                    <div><small class="text-muted">28 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $119</div>
                                                    <div><small class="text-muted">29 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-danger"></i>
                                                    <div class="font-w600">Paypal Withdrawal - $300</div>
                                                    <div><small class="text-muted">37 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $129</div>
                                                    <div><small class="text-muted">39 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $119</div>
                                                    <div><small class="text-muted">45 hours ago</small></div>
                                                </li>
                                                <li>
                                                    <i class="fa fa-circle text-success"></i>
                                                    <div class="font-w600">New sale! + $499</div>
                                                    <div><small class="text-muted">46 hours ago</small></div>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- END Yesterday -->

                                        <!-- More -->
                                        <div class="text-center">
                                            <small><a href="javascript:void(0)">Load More..</a></small>
                                        </div>
                                        <!-- END More -->
                                    </div>
                                </div>
                                <!-- END Sales Tab -->
                            </div>
                        </div>
                        <!-- END Side Overlay Tabs -->
                    </div>
                    <!-- END Side Content -->
                </div>
                <!-- END Side Overlay Scroll Container -->
            </aside>
            <!-- END Side Overlay -->

            <!-- Sidebar -->
            <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="side-header side-content bg-white-op">
                            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                            <div class="btn-group pull-right">
                                <button class="btn btn-link text-gray dropdown-toggle" data-toggle="dropdown" type="button">
                                    <i class="si si-drop"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right font-s13 sidebar-mini-hide">
                                    <li>
                                        <a data-toggle="theme" data-theme="default" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-default pull-right"></i> <span class="font-w600">Default</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-amethyst pull-right"></i> <span class="font-w600">Amethyst</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="theme" data-theme="assets/css/themes/city.min.css" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-city pull-right"></i> <span class="font-w600">City</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="theme" data-theme="assets/css/themes/flat.min.css" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-flat pull-right"></i> <span class="font-w600">Flat</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="theme" data-theme="assets/css/themes/modern.min.css" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-modern pull-right"></i> <span class="font-w600">Modern</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" tabindex="-1" href="javascript:void(0)">
                                            <i class="fa fa-circle text-smooth pull-right"></i> <span class="font-w600">Smooth</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a class="h5 text-white" href="index.html">
                                <i class="fa fa-circle-o-notch text-primary"></i> <span class="h4 font-w600 sidebar-mini-hide">ne</span>
                            </a>
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Content -->
                        <div class="side-content">
                            <ul class="nav-main">
                                <li>
                                    <a href="base_pages_dashboard.html"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">User Interface</span></li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">UI Elements</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_ui_widgets.html">Widgets</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Blocks</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_blocks.html">Styles</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_blocks_api.html">Blocks API</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_blocks_draggable.html">Draggable</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="base_ui_grid.html">Grid</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_typography.html">Typography</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_icons.html">Icons</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_buttons.html">Buttons</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_activity.html">Activity</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_tabs.html">Tabs</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_tiles.html">Tiles</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_cards.html">Cards</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_ribbons.html">Ribbons</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Chat</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_chat_full.html">Full</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_chat_fixed.html">Fixed</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_chat_popup.html">Popup</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Timeline</a>
                                            <ul>
                                                <li>
                                                    <a href="base_ui_timeline.html">Various</a>
                                                </li>
                                                <li>
                                                    <a href="base_ui_timeline_social.html">Social</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="base_ui_navigation.html">Navigation</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_modals_tooltips.html">Modals &amp; Tooltips</a>
                                        </li>
                                        <li>
                                            <a href="base_ui_color_themes.html">Color Themes</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-grid"></i><span class="sidebar-mini-hide">Tables</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_tables_styles.html">Styles</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_responsive.html">Responsive</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_tools.html">Tools</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_pricing.html">Pricing</a>
                                        </li>
                                        <li>
                                            <a href="base_tables_datatables.html">DataTables</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-note"></i><span class="sidebar-mini-hide">Forms</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_forms_premade.html">Pre-made</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_elements.html">Elements</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_pickers_more.html">Pickers &amp; More</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_editors.html">Text Editors</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_validation.html">Validation</a>
                                        </li>
                                        <li>
                                            <a href="base_forms_wizard.html">Wizard</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Develop</span></li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Components</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_comp_images.html">Images</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_charts.html">Charts (Various)</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_chartjs_v2.html">Charts.js v2</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_calendar.html">Calendar</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_sliders.html">Sliders</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_animations.html">Animations</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_scrolling.html">Scrolling</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_syntax_highlighting.html">Syntax Highlighting</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_rating.html">Rating</a>
                                        </li>
                                        <li>
                                            <a href="base_comp_treeview.html">Tree View</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Maps</a>
                                            <ul>
                                                <li>
                                                    <a href="base_comp_maps.html">Google</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_maps_full.html">Google Full</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_maps_vector.html">Vector</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Gallery</a>
                                            <ul>
                                                <li>
                                                    <a href="base_comp_gallery_simple.html">Simple</a>
                                                </li>
                                                <li>
                                                    <a href="base_comp_gallery_advanced.html">Advanced</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-magic-wand"></i><span class="sidebar-mini-hide">Layouts</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_layouts_api.html">Layout API</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_default.html">Default</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_default_flipped.html">Default Flipped</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_header_static.html">Static Header</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_sidebar_mini_hoverable.html">Mini Sidebar (Hoverable)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_overlay_hoverable.html">Side Overlay (Hoverable)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_overlay_open.html">Side Overlay (Open)</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_side_native_scrolling.html">Side Native Scrolling</a>
                                        </li>
                                        <li>
                                            <a href="base_layouts_sidebar_hidden.html">Hidden Sidebar</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Multi Level Menu</span></a>
                                    <ul>
                                        <li>
                                            <a href="#">Link 1-1</a>
                                        </li>
                                        <li>
                                            <a href="#">Link 1-2</a>
                                        </li>
                                        <li>
                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 2</a>
                                            <ul>
                                                <li>
                                                    <a href="#">Link 2-1</a>
                                                </li>
                                                <li>
                                                    <a href="#">Link 2-2</a>
                                                </li>
                                                <li>
                                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 3</a>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Link 3-1</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Link 3-2</a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 4</a>
                                                            <ul>
                                                                <li>
                                                                    <a href="#">Link 4-1</a>
                                                                </li>
                                                                <li>
                                                                    <a href="#">Link 4-2</a>
                                                                </li>
                                                                <li>
                                                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 5</a>
                                                                    <ul>
                                                                        <li>
                                                                            <a href="#">Link 5-1</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="#">Link 5-2</a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 6</a>
                                                                            <ul>
                                                                                <li>
                                                                                    <a href="#">Link 6-1</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="#">Link 6-2</a>
                                                                                </li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Pages</span></li>
                                <li class="open">
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">Generic</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_blank.html">Blank</a>
                                        </li>
                                        <li>
                                            <a class="active" href="base_pages_dashboard_v2.html">Dashboard v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_search.html">Search Results</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_invoice.html">Invoice</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_faq.html">FAQ</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_inbox.html">Inbox</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_files.html">Files</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_tickets.html">Tickets</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_contacts.html">Contacts</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_coming_soon.html">Coming Soon</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_coming_soon_v2.html">Coming Soon v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_maintenance.html">Maintenance</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bag"></i><span class="sidebar-mini-hide">e-Commerce</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_ecom_dashboard.html">Dashboard</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_orders.html">Orders</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_order.html">Order</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_products.html">Products</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_product_edit.html">Product Edit</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_ecom_customer.html">Customer</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-user"></i><span class="sidebar-mini-hide">User Profile</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_profile.html">Profile</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile_v2.html">Profile v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile_edit.html">Profile Edit</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bubbles"></i><span class="sidebar-mini-hide">Forum</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_forum_categories.html">Categories</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_topics.html">Topics</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_discussion.html">Discussion</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_forum_new_topic.html">New Topic</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-lock"></i><span class="sidebar-mini-hide">Authentication</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_auth.html">All Pages</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_login.html">Log In</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_login_v2.html">Log In v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_register.html">Register</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_register_v2.html">Register v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_lock.html">Lock Screen</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_lock_v2.html">Lock Screen v2</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_reminder.html">Password Reminder</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_reminder_v2.html">Password Reminder v2</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-fire"></i><span class="sidebar-mini-hide">Error Pages</span></a>
                                    <ul>
                                        <li>
                                            <a href="base_pages_errors.html">All Pages</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_400.html">400</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_401.html">401</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_403.html">403</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_404.html">404</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_500.html">500</a>
                                        </li>
                                        <li>
                                            <a href="base_pages_503.html">503</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- END Side Content -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="header-navbar" class="content-mini content-mini-full">
                <!-- Header Navigation Right -->
                <ul class="nav-header pull-right">
                    <li>
                        <div class="btn-group">
                            <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">
                                <img src="assets/img/avatars/avatar10.jpg" alt="Avatar">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-header">Profile</li>
                                <li>
                                    <a tabindex="-1" href="base_pages_inbox.html">
                                        <i class="si si-envelope-open pull-right"></i>
                                        <span class="badge badge-primary pull-right">3</span>Inbox
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="base_pages_profile.html">
                                        <i class="si si-user pull-right"></i>
                                        <span class="badge badge-success pull-right">1</span>Profile
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="javascript:void(0)">
                                        <i class="si si-settings pull-right"></i>Settings
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Actions</li>
                                <li>
                                    <a tabindex="-1" href="base_pages_lock.html">
                                        <i class="si si-lock pull-right"></i>Lock Account
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="base_pages_login.html">
                                        <i class="si si-logout pull-right"></i>Log out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="side_overlay_toggle" type="button">
                            <i class="fa fa-tasks"></i>
                        </button>
                    </li>
                </ul>
                <!-- END Header Navigation Right -->

                <!-- Header Navigation Left -->
                <ul class="nav-header pull-left">
                    <li class="hidden-md hidden-lg">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li>
                        <!-- Opens the Apps modal found at the bottom of the page, before including JS code -->
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#apps-modal" type="button">
                            <i class="si si-grid"></i>
                        </button>
                    </li>
                    <li class="visible-xs">
                        <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
                        <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search" data-class="header-search-xs-visible" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
                    <li class="js-header-search header-search">
                        <form class="form-horizontal" action="base_pages_search.html" method="post">
                            <div class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                                <input class="form-control" type="text" id="base-material-text" name="base-material-text" placeholder="Search..">
                                <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                            </div>
                        </form>
                    </li>
                </ul>
                <!-- END Header Navigation Left -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Header -->
                <div class="bg-image overflow-hidden" style="background-image: url('assets/img/photos/photo31@2x.jpg');">
                    <div class="bg-black-op">
                        <div class="content content-narrow">
                            <div class="block block-transparent">
                                <div class="block-content block-content-full">
                                    <h1 class="h1 font-w300 text-white animated fadeInDown push-50-t push-5">Dashboard</h1>
                                    <h2 class="h4 font-w300 text-white-op animated fadeInUp">Welcome Administrator</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Page Content -->
                <div class="content content-narrow">
                    <!-- Stats -->
                    <div class="row text-uppercase">
                        <div class="col-xs-6 col-sm-3">
                            <div class="block block-rounded">
                                <div class="block-content block-content-full">
                                    <div class="text-muted">
                                        <small><i class="si si-calendar"></i> Today</small>
                                    </div>
                                    <div class="font-s12 font-w700">Unq Visitors</div>
                                    <a class="h2 font-w300 text-primary" href="base_comp_charts.html" data-toggle="countTo" data-to="480950"></a>
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
                                    <a class="h2 font-w300 text-primary" href="base_comp_charts.html" data-toggle="countTo" data-to="495"></a>
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
                                    <a class="h2 font-w300 text-primary" href="base_comp_charts.html" data-toggle="countTo" data-to="148000" data-before="$"></a>
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
                                    <a class="h2 font-w300 text-primary" href="base_comp_charts.html" data-toggle="countTo" data-to="299" data-before="$"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Stats -->

                    <!-- Dashboard Charts -->
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
                                    <div style="height: 340px;"><canvas class="js-dash-chartjs-earnings"></canvas></div>
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
                                    <div style="height: 340px;"><canvas class="js-dash-chartjs-sales"></canvas></div>
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
                    <!-- END Dashboard Charts -->

                    <!-- Dashboard Cards -->
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- Add Friend -->
                            <div class="bg-image" style="background-image: url('assets/img/photos/photo29.jpg');">
                                <div class="bg-black-op">
                                    <div class="block block-themed block-transparent">
                                        <div class="block-header">
                                            <h3 class="block-title text-center">Friend Suggestion</h3>
                                        </div>
                                        <div class="block-content text-center">
                                            <div class="push">
                                                <img class="img-avatar img-avatar96 img-avatar-thumb" src="assets/img/avatars/avatar10.jpg" alt="">
                                            </div>
                                            <h3 class="font-w300 text-white">Eric Lawson</h3>
                                        </div>
                                        <div class="block-content block-content-full text-center">
                                            <a class="btn btn-sm btn-default" href="javascript:void(0)">
                                                <i class="fa fa-fw fa-plus text-success"></i> Add friend
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Add Friend -->
                        </div>
                        <div class="col-lg-4">
                            <!-- Category -->
                            <div class="bg-image" style="background-image: url('assets/img/photos/photo28.jpg');">
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
                                        <div class="block-content block-content-full text-center push-50-t push-50">
                                            <a class="h1 font-w300 text-white" href="javascript:void(0)">Photography</a>
                                        </div>
                                        <div class="block-content block-content-full text-center">
                                            <span class="text-white-op"><em>Updated 10 hours ago</em></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Category -->
                        </div>
                        <div class="col-lg-4">
                            <!-- Weather -->
                            <div class="block">
                                <div class="bg-image" style="background-image: url('assets/img/photos/photo33.jpg');">
                                    <div class="bg-black-op">
                                        <div class="block-content text-center">
                                            <h3 class="h2 font-w300 text-uppercase text-white push-50-t">San Francisco</h3>
                                            <h4 class="h5 text-white-op push-50">California, US</h4>
                                        </div>
                                        <div class="block-content block-content-full text-center bg-black-op">
                                            <div class="row push-5-t push-5">
                                                <div class="col-xs-4">
                                                    <div class="h2 font-w300 text-white">24&deg;C</div>
                                                    <div class="h5 text-muted push-5-t">MON</div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="h2 font-w300 text-white">26&deg;C</div>
                                                    <div class="h5 text-muted push-5-t">TUE</div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="h2 font-w300 text-white">25&deg;C</div>
                                                    <div class="h5 text-muted push-5-t">WED</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Weather -->
                        </div>
                    </div>
                    <!-- END Dashboard Cards -->
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
                <div class="pull-right">
                    Crafted with <i class="fa fa-heart text-city"></i> by <a class="font-w600" href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
                </div>
                <div class="pull-left">
                    <a class="font-w600" href="http://goo.gl/6LF10W" target="_blank">OneUI 3.1</a> &copy; <span class="js-year-copy"></span>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Apps Modal -->
        <!-- Opens from the button in the header -->
        <div class="modal fade" id="apps-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-sm modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <!-- Apps Block -->
                    <div class="block block-themed block-transparent">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Apps</h3>
                        </div>
                        <div class="block-content">
                            <div class="row text-center">
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="base_pages_dashboard.html">
                                        <div class="block-content text-white bg-default">
                                            <i class="si si-speedometer fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Backend</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="frontend_home.html">
                                        <div class="block-content text-white bg-modern">
                                            <i class="si si-rocket fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Frontend</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Apps Block -->
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->

        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="assets/js/core/jquery.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>
        <script src="assets/js/core/jquery.slimscroll.min.js"></script>
        <script src="assets/js/core/jquery.scrollLock.min.js"></script>
        <script src="assets/js/core/jquery.appear.min.js"></script>
        <script src="assets/js/core/jquery.countTo.min.js"></script>
        <script src="assets/js/core/jquery.placeholder.min.js"></script>
        <script src="assets/js/core/js.cookie.min.js"></script>
        <script src="assets/js/app.js"></script>

        <!-- Page Plugins -->
        <script src="assets/js/plugins/chartjs/Chart.min.js"></script>

        <!-- Page JS Code -->
        <script src="assets/js/pages/base_pages_dashboard_v2.js"></script>
        <script>
            jQuery(function () {
                // Init page helpers (CountTo plugin)
                App.initHelpers('appear-countTo');
            });
        </script>
    </body>
</html>