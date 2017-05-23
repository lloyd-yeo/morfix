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
                    <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#upgrade-dm-modal">
                        <div class="block-content block-content-full bg-primary clearfix">
                            <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                            <span class="h4 font-w700 text-white">Part 1</span> - <span class="h4 text-white-op">Dashboard</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#upgrade-dm-modal">
                        <div class="block-content block-content-full bg-primary clearfix">
                            <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                            <span class="h4 font-w700 text-white">Part 2</span> - <span class="h4 text-white-op">Profile</span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#upgrade-dm-modal">
                        <div class="block-content block-content-full bg-primary clearfix">
                            <i class="fa fa-play-circle fa-2x text-white pull-left"></i>
                            <span class="h4 font-w700 text-white">Part 3</span> - <span class="h4 text-white-op">Auto Interaction</span>
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