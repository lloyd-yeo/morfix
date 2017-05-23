@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'training-morfix'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-rocket"></i>  How-to-use Morfix <small> Learn how to utilize Morfix to it's greatest potential!</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="content content-boxed">
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#dashboard">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 1</span> - <span class="h4 text-white-op">Dashboard</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#profile">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 2</span> - <span class="h4 text-white-op">Profile</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#autointeraction">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 3</span> - <span class="h4 text-white-op">Auto Interaction</span>
                    </div>
                </a>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#directmessage">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 4</span> - <span class="h4 text-white-op">Direct Messages</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#photoscheduling">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 5</span> - <span class="h4 text-white-op">Post Scheduling</span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#dmalgorithm">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Part 6</span> - <span class="h4 text-white-op">Beating the DM Algorithm</span>
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
                                {{ $training_video_iframe }}
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