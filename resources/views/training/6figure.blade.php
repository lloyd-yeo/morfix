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
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Finding Great Content (Part 3)</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-createcontent">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Creating Instagram Content</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-posttiming">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Timing to Post</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-repost">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">How to Repost</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-whotofollow">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Who to Follow</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-hashtag">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Finding Hashtags</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-engagementgroup">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Engagement Group</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-analytics">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Instagram Analytics</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-shoutout">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Shoutout</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-influencer">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Reaching Out to Influencers</span>
                    </div>
                </a>
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#ig-rates">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w500 text-white">Part 7</span> - <span class="h4 text-white-op">Negotiate Rates</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    
</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection