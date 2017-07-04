@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'faq'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="fa fa-question-circle-o"></i> FAQ <small> Frequently asked questions.</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/general">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-direction text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">General Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                All general enquiries answered in this section! (What is Morfix and what are its functions?)
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/affiliate">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-users text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Affiliate System Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Have doubts about the attractive affiliate system that Morfix offers? Click here.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/interaction">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-rocket text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Auto Interactions Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Watched our training video & still unsure about Auto Interactions? Look in here!
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/dm">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-envelope text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Auto Direct Messages Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Everything related to Direct Messaging on Instagram inside here!
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/account">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-settings text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Account Setting Up Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Problems setting up your account? These articles will shed some light.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/billing">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-wallet text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Billing Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Feeling unsure about making payment? Let us help!
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/postscheduling">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-picture text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Post Scheduling Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Post Scheduling related issues can be addressed here!
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <a class="block block-link-hover2 text-center" href="/faq/engagementgroup">
                    <div class="block-content block-content-full border-b" style="overflow-y:auto; padding-bottom: 60px;">
                        <div class="col-lg-2 col-sm-3">
                            <span class="pull-left push-20-l"><i class="si si-like text-gray" style="font-size:8em;"></i></span>
                        </div>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left font-w600 push-20-l" style="font-size: 26px; color: #5c90d2;">Engagement Group Queries</p>
                        </div>
                        <br/><br/>
                        <div class="col-lg-9" style="float:left;">
                            <p class="pull-left push-20-l text-left">
                                Having issues with our Engagement Group function? Don't worry! It's all here.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
@endsection