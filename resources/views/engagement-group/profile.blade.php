@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'engagement-group'])
@endsection

@section('content')
<main id="main-container">
    
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-rocket"></i> Engagement Group <small> Choose an account below to boost engagements for.</small>
                </h1>
            </div>
        </div>
    </div>
    
    <div class="content content-narrow">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <!-- Crystal on Background Color -->
                <div class="block">
                    <div class="bg-image" style="background-image: url('{{ $ig_profile->profile_pic_url }}');">
                        <div class="block-content block-content-full bg-black-op ribbon ribbon-crystal">
                            <div class="ribbon-box font-w600"><i class="fa fa-check"></i> CURRENTLY EDITING</div>
                            <div class="text-center push-50-t push-50">
                                <h3 class="text-white-op"><i class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Crystal on Background Color -->
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <div class="block">
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="si si-picture"></i> {{ $ig_profile->insta_username }}'s Uploads</h3>
                    </div>
                    <div class="block-content">
                        <div id="personal-gallery" class="row items-push js-gallery-advanced">
                            @foreach (array_chunk($medias, 3) as $media_row)
                            <div class='row'>
                                @foreach ($media_row as $media)
                                <div class="col-sm-6 col-md-4 col-lg-4 animated fadeIn push-15">
                                    <div class="img-container fx-img-rotate-r">
                                        <img class="img-responsive" src="{{ $media->image_url }}" alt="">
                                        <div class="img-options">
                                            <div class="img-options-content">
                                                <h3 class="font-w400 text-white push-5">Send for engagement!</h3>
                                                <h4 class="h6 font-w400 text-white-op push-15">Click below</h4>
                                                <a class="btn btn-sm btn-default img-lightbox" href="{{ $media->image_url }}">
                                                    <i class="fa fa-search-plus"></i> View
                                                </a>
                                                <div class="btn-group btn-group-sm">
                                                    <a class="btn btn-default engagement-btn" data-profile-id="{{ $ig_profile->id }}" data-image-id="{{ $user_img->id }}" href="javascript:void(0)"><i class="fa fa-pencil"></i> Send for Engagement</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection