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
                            @foreach ($user_updates as $update)
                            @include('dashboard.update', ['update' => $update])
                            @endforeach
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


        <!-- Add Instagram Profile Modal -->
        <div class="modal fade" id="modal-addprofile" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-lg">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title"><i class='fa fa-instagram'></i> ADD PROFILE</h3>
                        </div>
                        <div class="block-content">
                            <!-- Validation Wizard (.js-wizard-validation class is initialized in js/pages/base_forms_wizard.js) -->
                            <!-- For more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/ -->
                            <div class="js-wizard-validation block">
                                <!-- Step Tabs -->
                                <ul class="nav nav-tabs nav-tabs-alt nav-justified">
                                    <li class="active">
                                        <a class="inactive" href="#validation-step1" data-toggle="tab">1. Link Instagram</a>
                                    </li>
                                    <li>
                                        <a class="inactive" href="#validation-step2" data-toggle="tab">2. Verification</a>
                                    </li>
                                    <li>
                                        <a class="inactive" href="#validation-step3" data-toggle="tab">3. Finish</a>
                                    </li>
                                </ul>
                                <!-- END Step Tabs -->

                                <!-- Form -->
                                <!-- jQuery Validation (.js-form2 class is initialized in js/pages/base_forms_wizard.js) -->
                                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-form2 form-horizontal" action="base_forms_wizard.html" method="post">
                                    <!-- Steps Content -->
                                    <div class="block-content tab-content">
                                        <!-- Step 1 -->
                                        <div class="tab-pane fade fade-right in push-30-t push-50 active" id="validation-step1">
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-material form-material-primary">
                                                        <input class="form-control" type="text" id="validation-ig-username" name="validation-ig-username" placeholder="Please enter your Instagram Username/Handle">
                                                        <label for="validation-ig-username">Instagram Username/Handle</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-material form-material-primary">
                                                        <input class="form-control" type="text" id="validation-ig-password" name="validation-ig-password" placeholder="Please enter your Instagram Password">
                                                        <label for="validation-ig-password">Instagram Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 1 -->

                                        <!-- Step 2 -->
                                        <div class="tab-pane fade fade-right push-30-t push-50" id="validation-step2">
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-material">
                                                        <div class='block'>
                                                            <center><h1 class='push text-modern'><i class='fa fa-info-circle'></i> Verification Required</h1></center>
                                                            <p class='lead'>
                                                                Morfix is unable to link-up your profile because of additional verification required from Instagram.
                                                                To solve this, follow the instructions below (3 mins):
                                                            </p>
                                                            <center><h4 class='push'>1. Login to Instagram with your account</h4></center>
                                                            <p>
                                                                Go to <a target='_blank' href='http://www.instagram.com'>www.instagram.com</a> & login with the account that you are trying to add to Morfix.
                                                                Leave the page on for now & go back to Morfix.
                                                            </p>
                                                            <center><h4 class='push'>2. Retry adding</h4></center>
                                                            <div>
                                                                <center><button class="btn btn-primary" type="button"><i class="fa fa-refresh fa-spin"></i> Retry</button></center>
                                                            </div>
                                                            <br/>
                                                            <p class='text-danger text-center'>
                                                                <b>It will fail again! Do not worry.</b>
                                                            </p>
                                                            <center><h4 class='push'>3. Verify "It was me"</h4></center>
                                                            <p>
                                                                Wait for the previous step to fail then switch back to Instagram & refresh the page.
                                                                You will now be presented with something like this:
                                                            </p>
                                                            <center><img src="{{ asset('assets/img/checkpoint/itwasme.jpeg') }}" style="width: 70%;" alt="It was me"></center>
                                                            <p>
                                                                Click "It was me" & then press "Ok".
                                                                After that browse to your profile's page & switch back to Morfix.
                                                            </p>
                                                            <center><h4 class='push'>4. Retry adding</h4></center>
                                                            <div>
                                                                <center><button class="btn btn-primary" type="button"><i class="fa fa-refresh"></i> Retry</button></center>
                                                            </div>
                                                            <p>
                                                                Depending on whether your account gets added or not.<br/>
                                                                Repeat the process from Step 3.<br/>
                                                                Try for up to a total of 10 times & if you still can't add, do contact live chat on the bottom right hand corner.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 2 -->

                                        <!-- Step 3 -->
                                        <div class="tab-pane fade fade-right push-30-t push-50" id="validation-step3">
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-material">
                                                        <input class="form-control" type="text" id="validation-city" name="validation-city" placeholder="Where do you live?">
                                                        <label for="validation-city">City</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <div class="form-material">
                                                        <select class="form-control" id="validation-skills" name="validation-skills" size="1">
                                                            <option value="">Please select your best skill</option>
                                                            <option value="1">Photoshop</option>
                                                            <option value="2">HTML</option>
                                                            <option value="3">CSS</option>
                                                            <option value="4">JavaScript</option>
                                                        </select>
                                                        <label for="validation-skills">Skills</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <label class="css-input switch switch-sm switch-primary" for="validation-terms">
                                                        <input type="checkbox" id="validation-terms" name="validation-terms"><span></span> Agree with the <a data-toggle="modal" data-target="#modal-terms" href="#">terms</a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Step 3 -->
                                    </div>
                                    <!-- END Steps Content -->

                                    <!-- Steps Navigation -->
                                    <div class="block-content block-content-mini block-content-full border-t">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button class="wizard-prev btn btn-warning" type="button"><i class="fa fa-arrow-circle-o-left"></i> Previous</button>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <button class="wizard-next btn btn-primary" type="button">Next <i class="fa fa-arrow-circle-o-right"></i></button>
                                                <button class="wizard-finish btn btn-primary" type="submit"><i class="fa fa-check-circle-o"></i> Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Steps Navigation -->
                                </form>
                                <!-- END Form -->
                            </div>
                            <!-- END Validation Wizard Wizard -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Add Instagram Profile Modal -->

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