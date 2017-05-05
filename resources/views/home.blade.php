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
            <div class='col-lg-4' style='height: 570px; margin-bottom:30px;'>
                <!-- Category -->
                <div class="col-lg-12 col-xs-12" style="height: 275px; margin-bottom: 20px;">
                    <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                        <div class="block-content block-content-full">
                            <!--<i class="si si-music-tone fa-4x text-primary-darker"></i>-->
                            <span class="h2 font-w700 text-primary animated flipInX" data-subject="money" data-toggle="countTo" data-decimals="2" data-to="100.0000">$100.00</span>
                            <div class="text-primary-dark font-w600 push-15-t">Pending Commission</div>
                        </div>
                    </a>
                    <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                        <div class="block-content block-content-full">
                            <!--<i class="si si-music-tone fa-4x text-primary-darker"></i>-->
                            <span class="h2 font-w700 text-primary animated flipInX" data-subject="money" data-toggle="countTo" data-decimals="2" data-to="100.0000">$100.00</span>
                            <div class="text-primary-dark font-w600 push-15-t">Pending Commission</div>
                        </div>
                    </a>
                </div>
                <!-- END Category -->

                <!-- Category -->
                <div class="bg-image col-lg-12 col-xs-12" style="height: 275px; background-image: url('assets/img/photos/photo25.jpg');">
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
                            <div class="block-content block-content-full text-center">
                                <a class="h1 font-w300 text-white" href="javascript:void(0)">Photography</a>
                            </div>
                            <div class="block-content block-content-full text-center">
                                <span class="text-white-op"><em>Updated 3 hours ago</em></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Category -->

            </div>

            <div class='col-lg-4' style='height: 570px; margin-bottom:30px;'>
                <!-- Category -->
                <div class="bg-image col-lg-12 col-xs-12" style="height: 275px; margin-bottom: 20px; background-image: url('assets/img/photos/photo25.jpg');">
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
                            <div class="block-content block-content-full text-center">
                                <a class="h1 font-w300 text-white" href="javascript:void(0)">Photography</a>
                            </div>
                            <div class="block-content block-content-full text-center">
                                <span class="text-white-op"><em>Updated 3 hours ago</em></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Category -->

                <!-- Category -->
                <div class="bg-image col-lg-12 col-xs-12" style="height: 275px; background-image: url('assets/img/photos/photo25.jpg');">
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
                            <div class="block-content block-content-full text-center">
                                <a class="h1 font-w300 text-white" href="javascript:void(0)">Photography</a>
                            </div>
                            <div class="block-content block-content-full text-center">
                                <span class="text-white-op"><em>Updated 3 hours ago</em></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Category -->
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