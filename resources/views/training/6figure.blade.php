@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'training-6figureprofile'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-star"></i>  6 Figure Profile <small> Learn the tips & tricks to build a 6 figure profile!</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-setup">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 1</span> - <span class="h4 text-white-op">Setting up Instagram to Win</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-bio">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 2</span> - <span class="h4 text-white-op">Bio & Link in Bio</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-secure">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 3</span> - <span class="h4 text-white-op">Securing your Account</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-accttype">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 4</span> - <span class="h4 text-white-op">Types of Accounts</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-content">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 5</span> - <span class="h4 text-white-op">Finding Great Content (Part 1)</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-content2">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 6</span> - <span class="h4 text-white-op">Finding Great Content (Part 2)</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-content3">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 7</span> - <span class="h4 text-white-op">Finding Great Content (Part 3)</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-createcontent">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 8</span> - <span class="h4 text-white-op">Creating Instagram Content</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-posttiming">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 9</span> - <span class="h4 text-white-op">Timing to Post</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-repost">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 10</span> - <span class="h4 text-white-op">How to Repost</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-whotofollow">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 11</span> - <span class="h4 text-white-op">Who to Follow</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-hashtag">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 12</span> - <span class="h4 text-white-op">Finding Hashtags</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-engagementgroup">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 13</span> - <span class="h4 text-white-op">Engagement Group</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-analytics">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 14</span> - <span class="h4 text-white-op">Instagram Analytics</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-shoutout">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 15</span> - <span class="h4 text-white-op">Shoutout</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-influencer">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 16</span> - <span class="h4 text-white-op">Reaching Out to Influencers</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-rates">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 17</span> - <span class="h4 text-white-op">Negotiate Rates</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @foreach($morfix_training_video as $training => $training_video_iframe)
    <!-- Apps Modal -->
    <!-- Opens from the button in the header -->
    <div class="modal fade" id="{{ $training }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-lg modal-dialog modal-dialog-top">
            <div class="modal-content">
                <!-- Apps Block -->
                <div class="block block-themed block-transparent">
                    <div class="block-header bg-modern">
                        <ul class="block-options">
                            <li>
                                <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title">{{ $morfix_training_video_header[$training] }}</h3>
                    </div>
                    <div class="block-content">
                        <div class='row'>
                            <div class="embed-responsive embed-responsive-16by9">
                                {!! $training_video_iframe !!}
                            </div>

                            <div class='row push-10-t'>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2" href='http://lifestylemafia.aweber.com' target='_blank' >
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="fa fa-2x fa-envelope-open text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white"> Autoresponder</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2" href='http://Lifestylemafia.OnlineSalesPro.com/demo' target='_blank' >
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="fa fa-2x fa-book text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white"> Opt-In pages</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2"  href="#" data-toggle="modal" data-target="#upgrade-training-video-modal">
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="si si-bag fa-2x text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white"> Upgrade Business</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class='row push-10-t'>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2" href='https://app.morfix.co/app/Morfix-Swipe-Files.docx' target='_blank' >
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="fa fa-2x fa-cloud-download text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white">Swipe Files</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2" href='http://Lifestylemafia.OnlineSalesPro.com/demo' target='_blank' >
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="fa fa-2x fa-cloud-download text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white">EBook Swipe</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <a class="block block-link-hover2" href='http://Lifestylemafia.OnlineSalesPro.com/demo' target='_blank' >
                                        <div class="block-content block-content-full bg-primary clearfix">
                                            <i class="fa fa-2x fa-cloud-download text-white pull-left"></i>
                                            <span class="h4 font-w500 text-white">Download EBook</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button id="closetutorial-btn" data-dismiss="modal" class="btn blue lighten-1" type="button">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Apps Block -->
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    @endforeach

</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection