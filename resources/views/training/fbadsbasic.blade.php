@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'training-fbads'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-12">
                <h1 class="page-heading">
                    <i class="si si-star"></i>  Facebook Ads <small> Learn how to market effectively using Facebook Ads in this training series!</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#introduction">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 1</span> - <span class="h4 text-white-op">Introduction</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#overview">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-2x fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 2</span> - <span class="h4 text-white-op">Overview</span>
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
                                    <a class="block block-link-hover2" href='https://morfix.co/app/ebook-swipe.docx' target='_blank' >
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
@endsection