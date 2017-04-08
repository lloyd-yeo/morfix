<!-- Header -->
<header id="header-navbar" class="content-mini content-mini-full">
    <!-- Header Navigation Right -->
    <ul class="nav-header pull-right">
        <li>
            <div class="btn-group">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
                    <!--<img src="assets/img/avatars/avatar10.jpg" alt="Avatar">-->
                    @if (Auth::user()->admin == 1)
                    <span class='label label-violet'><i class="fa fa-anchor" style='bottom: 0px;'></i> Administrator</span>
                    <span class='label label-primary'><i class="fa fa-diamond" style='bottom: 0px;'></i> Mastermind Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->vip == 1)
                    <span class='label label-navy'><i class="fa fa-star" style='bottom: 0px;'></i> VIP</span>
                    <span class='label label-primary'><i class="fa fa-diamond" style='bottom: 0px;'></i> Mastermind Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->tier == 2)
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
                    @elseif (Auth::user()->tier == 12)
                    <span class='label label-primary'><i class="fa fa-briefcase" style='bottom: 0px;'></i> Business Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
                    @elseif (Auth::user()->tier == 22)
                    <span class='label label-primary'><i class="fa fa-diamond" style='bottom: 0px;'></i> Mastermind Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
                    @elseif (Auth::user()->tier == 3)
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->tier == 13)
                    <span class='label label-primary'><i class="fa fa-briefcase" style='bottom: 0px;'></i> Business Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->tier == 23)
                    <span class='label label-primary'><i class="fa fa-diamond" style='bottom: 0px;'></i> Mastermind Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->tier == 1)
                    <span class='label label-success'><i class="fa fa-check" style='bottom: 0px;'></i> Free Account</span>
                    @endif
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">Profile</li>
                    <!--                                <li>
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
                                                            <i class="si si-settings pull-right"></i>
                                                            <span class="badge badge-success pull-right">1</span>Settings
                                                        </a>
                                                    </li>-->
                    <li>
                        <a tabindex="-1" href="javascript:void(0)">
                            <i class="si si-settings pull-right"></i>Settings
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li class="dropdown-header">Actions</li>
                    <!--                                <li>
                                                        <a tabindex="-1" href="base_pages_lock.html">
                                                            <i class="si si-lock pull-right"></i>Lock Account
                                                        </a>
                                                    </li>-->
                    <li>
                        <a tabindex="-1" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                            <i class="si si-logout pull-right"></i>Log out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </li>
        <!--<li>-->
            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
<!--            <button class="btn btn-default" data-toggle="layout" data-action="side_overlay_toggle" type="button">
                <i class="fa fa-tasks"></i>
            </button>-->
        <!--</li>-->
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