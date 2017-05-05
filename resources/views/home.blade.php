@extends('layouts.app')

@section('css')
@include('dashboard.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'home'])
@endsection

@section('content')
<main id="main-container" style="padding-top: 10px;">
    @include('dashboard.hero')
    @include('dashboard.statistics')
    <!-- Page Content -->
    <div class="content">
        <div class="row">
            <div class="col-lg-8">
                <!-- News -->
                <div class="block">
                    <div class="block-header">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title"> Updates</h3>
                    </div>
                    <div class="block-content">
                        <ul class="list list-timeline pull-t">
                            <!-- Twitter Notification -->
                            <li>
                                <div class="list-timeline-time">12 hrs ago</div>
                                <i class="fa fa-twitter list-timeline-icon bg-info"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">+ 1150 Followers</p>
                                    <p class="font-s13">You’re getting more and more followers, keep it up!</p>
                                </div>
                            </li>
                            <!-- END Twitter Notification -->

                            <!-- Generic Notification -->
                            <li>
                                <div class="list-timeline-time">4 hrs ago</div>
                                <i class="fa fa-briefcase list-timeline-icon bg-city"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">+ 3 New Products were added!</p>
                                    <p class="font-s13">Congratulations!</p>
                                </div>
                            </li>
                            <!-- END Generic Notification -->

                            <!-- System Notification -->
                            <li>
                                <div class="list-timeline-time">1 day ago</div>
                                <i class="fa fa-check list-timeline-icon bg-success"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">Database backup completed!</p>
                                    <p class="font-s13">Download the <a href="javascript:void(0)">latest backup</a>.</p>
                                </div>
                            </li>
                            <!-- END System Notification -->

                            <!-- Facebook Notification -->
                            <li>
                                <div class="list-timeline-time">3 hrs ago</div>
                                <i class="fa fa-facebook list-timeline-icon bg-default"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">+ 290 Page Likes</p>
                                    <p class="font-s13">This is great, keep it up!</p>
                                </div>
                            </li>
                            <!-- END Facebook Notification -->

                            <!-- Social Notification -->
                            <li>
                                <div class="list-timeline-time">2 days ago</div>
                                <i class="fa fa-user-plus list-timeline-icon bg-modern"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">+ 3 Friend Requests</p>
                                    <ul class="nav-users push-10-t push">
                                        <li>
                                            <a href="base_pages_profile.html">
                                                <img class="img-avatar" src="assets/img/avatars/avatar11.jpg" alt="">
                                                <i class="fa fa-circle text-success"></i> Ethan Howard
                                                <div class="font-w400 text-muted"><small>Graphic Designer</small></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile.html">
                                                <img class="img-avatar" src="assets/img/avatars/avatar6.jpg" alt="">
                                                <i class="fa fa-circle text-warning"></i> Lisa Gordon
                                                <div class="font-w400 text-muted"><small>Photographer</small></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="base_pages_profile.html">
                                                <img class="img-avatar" src="assets/img/avatars/avatar16.jpg" alt="">
                                                <i class="fa fa-circle text-danger"></i> Walter Fox
                                                <div class="font-w400 text-muted"><small>UI Designer</small></div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <!-- END Social Notification -->

                            <!-- System Notification -->
                            <li class="push-5">
                                <div class="list-timeline-time">1 week ago</div>
                                <i class="fa fa-cog list-timeline-icon bg-primary-dark"></i>
                                <div class="list-timeline-content">
                                    <p class="font-w600">System updated to v2.02</p>
                                    <p class="font-s13">Check the complete changelog at the <a href="javascript:void(0)">activity page</a>.</p>
                                </div>
                            </li>
                            <!-- END System Notification -->
                        </ul>
                    </div>
                </div>
                <!-- END News -->
            </div>


            @include('dashboard.leaderboard')

            <script type="text/javascript">
                //var $leaderboardHeight = $("#leaderboard-container").height();
                //var $affiliateChartHeaderHeight = $("#affiliate-chart-header").height();
                //var $affiliateChartFooterHeight = $("#affiliate-chart-footer").height();
                //var $affiliateChartHeight = $leaderboardHeight - $affiliateChartHeaderHeight - $affiliateChartFooterHeight - 20 - 20 - 20 - 15 - 15;
                //$("#affiliate-chart").css("height", $affiliateChartHeight + "px");
            </script>
        </div>
        <div class="row">
            @foreach ($user_ig_profiles as $ig_profile)

            <div class="col-lg-4 insta-profile" data-id="{{ $ig_profile->id }}">
                <!-- Content Grid -->
                <div class="content-grid">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Instagram Profile -->
                            <div class="block block-themed" target="_blank" href="https://www.instagram.com/{{ $ig_profile->insta_username }}/">
                                <div class="block-header bg-primary">
                                    <ul class="block-options">
                                        <li>
                                            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                        </li>
                                        <li>
                                            <button type="button" data-id="{{ $ig_profile->id }}" data-user-id="{{ Auth::user()->id }}" class="remove-profile-btn"><i class="si si-close"></i></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title text-white"><i class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}</h3>
                                </div>
                                <div class="block-content block-content-full text-center bg-image" style="background-image: url('assets/img/photos/photo2.jpg');">
                                    <div>
                                        <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ $ig_profile->profile_pic_url }}" alt="">
                                    </div>
                                    <div class="h5 text-white push-15-t push-5"> {{ $ig_profile->profile_full_name }}</div>
                                    <!--<div class="h5 text-white-op">Web Developer</div>-->
                                </div>
                                <div class="block-content text-primary">
                                    <div class="row items-push text-center font-w700">
                                        <div class="col-xs-6">
                                            <div class="push-5"><i class="si si-camera fa-2x"></i></div>
                                            <div class="h5 font-w300">{{ $ig_profile->num_posts }} Posts</div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="push-5"><i class="si si-users fa-2x"></i></div>
                                            <div class="h5 font-w300">{{ $ig_profile->follower_count }} Followers</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Instagram Profile -->

                            <div class="block" href="javascript:void(0)">
                                <table class="block-table text-center">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%;">
                                                <div class="push-30 push-30-t">
                                                    <i class="si si-users fa-3x text-primary-dark"></i>
                                                </div>
                                            </td>
                                            <td class="bg-gray-lighter" style="width: 50%;">
                                                @if ($user_ig_new_follower[$ig_profile->insta_username] >= 0)
                                                <div class="h1 font-w700"><span class="h2 text-muted">+</span> {{ abs($user_ig_new_follower[$ig_profile->insta_username]) }}</div>
                                                @else
                                                <div class="h1 font-w700"><span class="h2 text-muted">-</span> {{ abs($user_ig_new_follower[$ig_profile->insta_username]) }}</div>
                                                @endif
                                                <div class="h5 text-muted text-uppercase push-5-t"> Followers</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- END Mini Stats -->
                        </div>
                    </div>
                </div>
                <!-- END Content Grid -->
            </div>
            <div class="col-lg-8 follower-chart">
                <div class="block">
                    <div id="follower-chart-header-{{ $ig_profile->id }}" class="block-header bg-primary">
                        <h3 class="block-title"><i class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}'s DAILY FOLLOWER TRENDS</h3>
                    </div>

                    <div class="block-content block-content-full bg-gray-lighter text-center chart-container">
                        <!--Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/--> 
                        <div id="follower-count-chart-{{ $ig_profile->id }}" style="height: 374px;" >
                            <canvas class="follower-count-chart-lines" data-csv="{{ $user_ig_analysis[$ig_profile->insta_username] }}" data-label="{{ $user_ig_analysis_label[$ig_profile->insta_username] }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-4">
                <!-- Material (floating) Login -->
                <div class="block block-themed">
                    <div class="block-header bg-primary">
                        <h3 class="block-title"><i class="fa fa-plus"></i> Add a new Instagram Profile</h3>
                    </div>
                    <div class="block-content">
                        <form class="form-horizontal push-10-t push-10" method="post" id="add-instagram-profile-form">
                            <input type="hidden" name="user-id" value="{{ Auth::user()->id }}"/> 
                            <input type="hidden" name="user-email" value="{{ Auth::user()->email }}"/> 
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material floating">
                                        <input class="form-control" type="text" id="login3-username" name="ig-username">
                                        <label for="login3-username">Instagram Username</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material floating">
                                        <input class="form-control" type="text" id="login3-password" name="ig-password">
                                        <label for="login3-password">Instagram Password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-arrow-right push-5-r"></i> Add Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Material (floating) Login -->
            </div>
        </div>

        <script type="text/javascript">
            $(".insta-profile").each(function (index) {
                var $instaId = $(this).attr("data-id");
                $("#follower-count-chart-" + $instaId).css("height", ($(this).height() - 24 - 40 - $("#follower-chart-header-" + $instaId).height() - 15 - 15) + "px");
            });
        </script>
    </div>
    <!-- END Page Content -->
</main>
@endsection

@section('js')
@include('dashboard.js')
@endsection