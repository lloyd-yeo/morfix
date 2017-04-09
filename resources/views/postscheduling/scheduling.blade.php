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

        <div class="col-lg-12">
            <!-- DropzoneJS -->
            <!-- For more info and examples you can check out http://www.dropzonejs.com/#usage -->
            <h2 class="content-heading">Upload your photos here!</h2>
            <div class="block">
                <div class="block-content block-content-full">
                    <!-- DropzoneJS Container -->
                    <form class="dropzone" action="add"></form>
                </div>
            </div>
            <!-- END DropzoneJS -->
        </div>

        <div class="col-xs-12 col-lg-12">
            <!-- Sizes -->
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="si si-picture"></i> MY PHOTOS</h3>
                </div>

                <div class="block-content">
                    <div class="row items-push js-gallery-advanced">
                        <div class="col-sm-6 col-md-4 col-lg-4 animated fadeIn">
                            <div class="img-container fx-img-rotate-r">
                                <img class="img-responsive" src="{{ asset('assets/img/photos/photo17.jpg') }}" alt="">
                                <div class="img-options">
                                    <div class="img-options-content">
                                        <h3 class="font-w400 text-white push-5">Image Caption</h3>
                                        <h4 class="h6 font-w400 text-white-op push-15">Some Extra Info</h4>
                                        <a class="btn btn-sm btn-default img-lightbox" href="{{ asset('assets/img/photos/photo17@2x.jpg') }}">
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

        <div class="col-xs-12 col-lg-12">
            <h2 class="content-heading">Morfix Stock Images</h2>
            <!-- Block Tabs Justified Alternative Style -->
            <div class="block">
                <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
                    @foreach ($default_img_category as $category)
                    @if ($category->id == 1)
                    <li class="active">
                        <a href="#cat-{{ $category->category }}"><i class="fa fa-tag"></i> {{ $category->category }}</a>
                    </li>
                    @else
                    <li>
                        <a href="#cat-{{ $category->category }}"><i class="fa fa-tag"></i> {{ $category->category }}</a>
                    </li>
                    @endif
                    @endforeach
                    <!--                    <li class="active">
                                            <a href="#btabs-alt-static-justified-home"><i class="fa fa-home"></i> Home</a>
                                        </li>
                                        <li>
                                            <a href="#btabs-alt-static-justified-profile"><i class="fa fa-pencil"></i> Profile</a>
                                        </li>
                                        <li>
                                            <a href="#btabs-alt-static-justified-settings"><i class="fa fa-cog"></i> Settings</a>
                                        </li>-->
                </ul>

                <div class="block-content tab-content">
                    @foreach ($default_img_category as $category)
                    @if ($category->id == 1)
                    <div class="tab-pane active" id="cat-{{ $category->category }}">
                        @else
                        <div class="tab-pane" id="cat-{{ $category->category }}">
                            @endif
                            @foreach ($imgs[$category->id]->chunk(6) as $default_imgs)
                            <div class='row'>
                                @foreach ($default_imgs as $default_img)
                                <div class="col-sm-3 col-md-2 col-lg-2 animated fadeIn push-15">
                                    <div class="img-container fx-img-rotate-r">
                                        <img class="img-responsive" src="{{ asset("storage/" . $default_img->image_path) }}" alt="">
                                        <div class="img-options">
                                            <div class="img-options-content">
                                                <h3 class="font-w400 text-white push-5">Upload this photo!</h3>
                                                <h4 class="h6 font-w400 text-white-op push-15">Click below</h4>
                                                <a class="btn btn-sm btn-default img-lightbox" href="{{ asset("storage/" . $default_img->image_path) }}">
                                                    <i class="fa fa-search-plus"></i> View
                                                </a>
                                                <div class="btn-group btn-group-sm">
                                                    <a class="btn btn-default upload-default-photo" data-image-id="{{ $default_img->image_id }}" href="javascript:void(0)"><i class="fa fa-pencil"></i> Schedule</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                        <!--                    <div class="tab-pane active" id="btabs-alt-static-justified-home">
                                                <h4 class="font-w300 push-15">Home Tab</h4>
                                                <p>...</p>
                                            </div>
                                            <div class="tab-pane" id="btabs-alt-static-justified-profile">
                                                <h4 class="font-w300 push-15">Profile Tab</h4>
                                                <p>...</p>
                                            </div>
                                            <div class="tab-pane" id="btabs-alt-static-justified-settings">
                                                <h4 class="font-w300 push-15">Settings Tab</h4>
                                                <p>...</p>
                                            </div>-->
                    </div>
                </div>
                <!-- END Block Tabs Justified Default Style -->
            </div>

        </div>

    </div>
    @endforeach

    @endsection

    @section('js')
    @include('postscheduling.js')
    @endsection