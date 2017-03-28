@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'postscheduling'])
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                <i class="si si-picture"></i>  Post Scheduling <small> Upload & schedule your posts!</small>
            </h1>
        </div>
    </div>
</div>
@foreach ($user_ig_profiles as $ig_profile)
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
            <!-- Sizes -->
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-envelope"></i> GREETING MESSAGE TEMPLATE</h3>
                </div>

                <div class="block-content">
                    <form class="form-horizontal push-10">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    @if (empty($ig_profile->insta_new_follower_template)) 
                                    <textarea class="form-control" id="greeting-template-txt" name="greeting-template" rows="7" placeholder="Type in your greeting message template here..."></textarea>
                                    @else
                                    <textarea class="form-control" id="greeting-template-txt" name="greeting-template" rows="7" placeholder="Type in your greeting message template here...">{{ $ig_profile->insta_new_follower_template }}</textarea>
                                    @endif

                                    <label for="greeting-template">Greeting Message Template</label>
                                </div>
                                <div class="help-block text-right">
                                    <b>PLACEHOLDER</b>
                                    <br/>
                                    ${full_name} - Type this as a placeholder for the recipient's instagram full name.
                                    <br/>
                                    ${Hey|Hi|Hello} - Enclose your words in a curly bracket so the system will randomly select a word to use.
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-primary" type="button" id="greeting-btn" data-id="{{ $ig_profile->id }}"><i class="fa fa-send push-5-r"></i> Save Template</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- END Sizes -->
        </div>

        <div class="col-xs-12 col-lg-12">
            <!-- Sizes -->
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-envelope"></i> FOLLOW-UP MESSAGE TEMPLATE</h3>
                </div>

                <div class="block-content">
                    <div class="row items-push js-gallery-advanced">
                        <div class="col-sm-6 col-md-4 col-lg-3 animated fadeIn">
                            <div class="img-container fx-img-rotate-r">
                                <img class="img-responsive" src="assets/img/photos/photo17.jpg" alt="">
                                <div class="img-options">
                                    <div class="img-options-content">
                                        <h3 class="font-w400 text-white push-5">Image Caption</h3>
                                        <h4 class="h6 font-w400 text-white-op push-15">Some Extra Info</h4>
                                        <a class="btn btn-sm btn-default img-lightbox" href="assets/img/photos/photo17@2x.jpg">
                                            <i class="fa fa-search-plus"></i> View
                                        </a>
                                        <div class="btn-group btn-group-sm">
                                            <a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-pencil"></i> Edit</a>
                                            <a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-times"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 animated fadeIn">
                            <div class="img-container fx-img-rotate-r">
                                <img class="img-responsive" src="assets/img/photos/photo16.jpg" alt="">
                                <div class="img-options">
                                    <div class="img-options-content">
                                        <h3 class="font-w400 text-white push-5">Image Caption</h3>
                                        <h4 class="h6 font-w400 text-white-op push-15">Some Extra Info</h4>
                                        <a class="btn btn-sm btn-default img-lightbox" href="assets/img/photos/photo16@2x.jpg">
                                            <i class="fa fa-search-plus"></i> View
                                        </a>
                                        <div class="btn-group btn-group-sm">
                                            <a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-pencil"></i> Edit</a>
                                            <a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-times"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Sizes -->
        </div>
    </div>

</div>
@endforeach

@endsection

@section('js')
@include('postscheduling.js')
@endsection