<!-- Sidebar -->
<nav id="sidebar" style="background-color: #1f4966;">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="side-header side-content bg-white-op">
                <a class="h5 text-white" href="/home">
                    <span class="h4 font-w600 sidebar-mini-hide">Morfix</span>
                </a>
                <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="side-content">
                <ul class="nav-main">
                    <li>
                        @if ($page == 'home')
                        <a class="active" href="/home"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @else
                        <a href="/home"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @endif
                    </li>
                    
                    @if (Auth::user()->admin == 1)
                    <li>
                        @if ($page == 'admin')
                        <a class="active" href="/admin"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Admin Dashboard</span></a>
                        @else
                        <a href="/admin"><i class="si si-wrench"></i><span class="sidebar-mini-hide">Admin Dashboard</span></a>
                        @endif
                    </li>
                    @endif

                    @if (Auth::user()->admin == 1)
                        <li>
                            @if ($page == 'mailer')
                                <a class="active" href="/mailer"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Administrative Mailer</span></a>
                            @else
                                <a href="/mailer"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Administrative Mailer</span></a>
                            @endif
                        </li>
                    @endif

                    <li class="nav-main-heading"><span class="sidebar-mini-hide">AUTOMATION TOOLS</span></li>

                    <li>
                        @if ($page == 'interaction')
                        <a class="active" href="/interactions"><i class="si si-rocket"></i><span class="sidebar-mini-hide">Interactions</span></a>
                        @else
                        <a href="/interactions"><i class="si si-rocket"></i><span class="sidebar-mini-hide">Interactions</span></a>
                        @endif
                    </li>

                    <li>
                        @if ($page == 'dm')
                        <a class="active" href="/dm"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Direct Message</span></a>
                        @else
                        <a href="/dm"><i class="si si-envelope"></i><span class="sidebar-mini-hide">Direct Message</span></a>
                        @endif
                    </li>

                    <li>
                        @if ($page == 'postscheduling')
                        <a class="active" href="/post-scheduling"><i class="si si-picture"></i><span class="sidebar-mini-hide">Post Scheduling</span></a>
                        @else
                        <a href="/post-scheduling"><i class="si si-picture"></i><span class="sidebar-mini-hide">Post Scheduling</span></a>
                        @endif
                    </li>

                    <li class="nav-main-heading"><span class="sidebar-mini-hide">VIRAL ENGAGEMENT</span></li>
                    <li>
                        @if ($page == 'engagement-group')
                        <a class="active" href="/engagement-group"><i class="si si-picture"></i><span class="sidebar-mini-hide">Engagement Group</span></a>
                        @else
                        @if (Auth::user()->tier > 3 || Auth::user()->vip == 1)
                        <a href="/engagement-group"><i class="si si-picture"></i><span class="sidebar-mini-hide">Engagement Group</span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-engagement-group-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Engagement Group</span></a>
                        @endif
                        @endif
                    </li>

                    <li class="nav-main-heading"><span class="sidebar-mini-hide">AFFILIATE AREA</span></li>
                    @if ((Auth::user()->is_competitor == 1 && Auth::user()->tier > 1) || Auth::user()->admin == 1)
                    <li>
                        @if ($page == 'competition')
                            <a class="active" href="/competition"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Competition</span></a>
                        @else
                            <a href="/competition"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Competition</span></a>
                        @endif
                    </li>
                    @endif
                    <li>
                        @if ($page == 'affiliate')
                        <a class="active" href="/affiliate"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @else
                        @if (Auth::user()->tier > 1)
                        <a href="/affiliate"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-affiliate-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                        @endif
                        @endif
                    </li>
                    <li>
                        @if ($page == 'affiliate-builder')
                        <a class="active"  href="javascript:void(0)"><i class="si si-magic-wand"></i><span class="sidebar-mini-hide">Landing Pages <small>[coming soon!]</small></span></a>
                        @else
                        @if (Auth::user()->tier > 1)
                        <a href="javascript:void(0)"><i class="si si-magic-wand"></i><span class="sidebar-mini-hide">Landing Pages <small>[coming soon!]</small></span></a>
                        @else
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#upgrade-affiliate-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Landing Pages <small>[coming soon!]</small></span></a>
                        @endif
                        @endif
                    </li>

                    @if(Auth::user()->vip != 1)
                    <li class="nav-main-heading"><span class="sidebar-mini-hide">UPGRADE ACCOUNT</span></li>
                    <li>
                        <a href="#" data-toggle="modal" data-target="#upgrade-modal"><i class="si si-bag"></i><span class="sidebar-mini-hide">Purchase</span></a>
                    </li>
                    @endif

                    <li class="nav-main-heading"><span class="sidebar-mini-hide">TRAINING VIDEOS</span></li>
                    <li>
                        @if ($page == 'training-morfix')
                        <a class="active" href="/training/morfix"><i class="si si-layers"></i><span class="sidebar-mini-hide">How-to-use Morfix</span></a>
                        @else
                        <a href="/training/morfix"><i class="si si-layers"></i><span class="sidebar-mini-hide">How-to-use Morfix</span></a>
                        @endif
                    </li>

                    <li>
                        @if ($page == 'training-bootcamp')
                        <a class="active" href="/training/bootcamp"><i class="si si-layers"></i><span class="sidebar-mini-hide">Morfix Bootcamp</span></a>
                        @else
                        @if (Auth::user()->tier > 1)
                        <a href="/training/bootcamp"><i class="si si-layers"></i><span class="sidebar-mini-hide">Morfix Bootcamp</span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-training-affiliate-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Morfix Bootcamp</span></a>
                        @endif
                        @endif
                    </li>

                    <li>
                        @if ($page == 'training-affiliate')
                        <a class="active" href="/training/affiliate"><i class="si si-layers"></i><span class="sidebar-mini-hide">Affiliate Training</span></a>
                        @else
                        @if (Auth::user()->tier > 1)
                        <a href="/training/affiliate"><i class="si si-layers"></i><span class="sidebar-mini-hide">Affiliate Training</span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-training-affiliate-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Affiliate Training</span></a>
                        @endif
                        @endif
                    </li>

                    <li>
                        @if ($page == 'training-6figureprofile')
                        <a class="active" href="/training/6figureprofile"><i class="si si-layers"></i><span class="sidebar-mini-hide">Build a 6-Figure Profile</span></a>
                        @else
                        @if ($page == 'training-6figureprofile')
                        <a href="/training/6figureprofile"><i class="si si-layers"></i><span class="sidebar-mini-hide">Build a 6-Figure Profile</span></a>
                        @else
                        @if ((Auth::user()->tier % 10) == 3)
                        <a href="/training/6figureprofile"><i class="si si-layers"></i><span class="sidebar-mini-hide">Build a 6-Figure Profile</span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-training-6figure-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Build a 6-Figure Profile</span></a>
                        @endif
                        @endif
                        @endif
                    </li>

                    <li>
                        @if ($page == 'training-fbads')
                        <a class="active" href="/training/fbadsbasic"><i class="fa fa-facebook"></i><span class="sidebar-mini-hide">Facebook Ads <small>[Business Only]</small></span></a>
                        @else
                        @if ($page == 'training-fbads')
                        <a href="/training/fbadsbasic"><i class="fa fa-facebook"></i><span class="sidebar-mini-hide">Facebook Ads <small>[Business Only]</small></span></a>
                        @else
                        @if (Auth::user()->tier > 3)
                        <a href="/training/fbadsbasic"><i class="fa fa-facebook"></i><span class="sidebar-mini-hide">Facebook Ads <small>[Business Only]</small></span></a>
                        @else
                        <a href="#" data-toggle="modal" data-target="#upgrade-training-video-modal"><i class="si si-lock"></i><span class="sidebar-mini-hide">Facebook Ads <small>[Business Only]</small></span></a>
                        @endif
                        @endif
                        @endif
                    </li>

                    <li class="nav-main-heading"><span class="sidebar-mini-hide">Support</span></li>

                    <li>
                        @if ($page == 'faq')
                        <a class="active" href="/faq"><i class="fa fa-question-circle-o"></i><span class="sidebar-mini-hide">FAQ</span></a>
                        @else
                        <a href="/faq"><i class="fa fa-question-circle-o"></i><span class="sidebar-mini-hide">FAQ</span></a>
                        @endif
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

