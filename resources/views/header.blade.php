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
                    @elseif (Auth::user()->tier == 3)
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Pro Account</span>
                    @elseif (Auth::user()->tier == 4)
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
                    <span class='label label-primary'><i class="fa fa-briefcase" style='bottom: 0px;'></i> Business Account</span>
                    @elseif (Auth::user()->tier == 12)
                    <span class='label label-primary'><i class="fa fa-briefcase" style='bottom: 0px;'></i> Business Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
                    @elseif (Auth::user()->tier == 22)
                    <span class='label label-primary'><i class="fa fa-diamond" style='bottom: 0px;'></i> Mastermind Account</span>
                    <span class='label label-danger'><i class="fa fa-star" style='bottom: 0px;'></i> Premium Account</span>
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
                    <li>
                        <a tabindex="-1" href="/settings">
                            <i class="si si-settings pull-right"></i>Settings
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li class="dropdown-header">Actions</li>
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
    </ul>
    <!-- END Header Navigation Right -->

    <!-- Header Navigation Left -->
    <ul class="nav-header pull-left">
        <li class="hidden-md hidden-lg">
            <!--Layout API, functionality initialized in App() -> uiLayoutApi()--> 
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                <i class="fa fa-navicon"></i>
            </button>
        </li>
        <li class="hidden-xs hidden-sm">
            <!--Layout API, functionality initialized in App() -> uiLayoutApi()--> 
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                <i class="fa fa-ellipsis-v"></i>
            </button>
        </li>
        <li class="hidden-xs hidden-sm">
            <!--Layout API, functionality initialized in App() -> uiLayoutApi()--> 
            <button class="btn text-black" type="button" onclick="javascript:location.href = 'faq'">
                <i class="fa fa-question-circle-o"></i> FAQ
            </button>
        </li>
    </ul>
    <!-- END Header Navigation Left -->
</header>
<!-- END Header -->