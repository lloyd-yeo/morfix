@extends('layouts.app')

@section('css')
@include('postscheduling.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'postscheduling'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-picture"></i>  Post Scheduling <small> Upload & schedule your posts!</small>
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

            <div class="col-lg-12">
                <!-- DropzoneJS -->
                <!-- For more info and examples you can check out http://www.dropzonejs.com/#usage -->
                <h2 class="content-heading">Upload your photos here!</h2>
                <p>Note: Instagram only accepts photos with a maximum resolution up to 1080x1350, and aspect ratios between 0.80 and 1.91.</p>
                <div class="block">
                    <div class="block-content block-content-full">
                        <!-- DropzoneJS Container -->
                        <form class="dropzone" id="image-upload" action="../add">
                        </form>
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
                        <div id="personal-gallery" class="row items-push js-gallery-advanced">
                            @foreach (array_chunk($user_images, 3) as $user_imgs)
                            <div class='row'>
                                @foreach ($user_imgs as $user_img)
                                <div class="col-sm-6 col-md-4 col-lg-4 animated fadeIn push-15">
                                    <div class="img-container fx-img-rotate-r">
                                        <img class="img-responsive" src="{{ asset("storage/" . $user_img->image_path) }}" alt="">
                                        <div class="img-options">
                                            <div class="img-options-content">
                                                <h3 class="font-w400 text-white push-5">Upload this photo!</h3>
                                                <h4 class="h6 font-w400 text-white-op push-15">Click below</h4>
                                                <a class="btn btn-sm btn-default img-lightbox" href="{{ asset("storage/" . $user_img->image_path) }}">
                                                    <i class="fa fa-search-plus"></i> View
                                                </a>
                                                <div class="btn-group btn-group-sm">
                                                    <a class="btn btn-default upload-photo" data-profile-id="{{ $ig_profile->id }}" data-image-id="{{ $user_img->id }}" href="javascript:void(0)"><i class="fa fa-pencil"></i> Schedule</a>
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
                    </ul>

                    <div class="block-content tab-content">
                        @foreach ($default_img_category as $category)
                        @if ($category->id == 1)
                        <div class="tab-pane active" id="cat-{{ $category->category }}">
                            @else
                            <div class="tab-pane" id="cat-{{ $category->category }}">
                                @endif
                                @foreach ($imgs[$category->id]->chunk(4) as $default_imgs)
                                <div class='row'>
                                    @foreach ($default_imgs as $default_img)
                                    <div class="col-sm-3 col-md-3 col-lg-3 animated fadeIn push-15">
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
                                                        <a class="btn btn-default upload-default-photo" data-profile-id="{{ $ig_profile->id }}" data-image-id="{{ $default_img->image_id }}" href="javascript:void(0)"><i class="fa fa-pencil"></i> Schedule</a>
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
                        </div>
                    </div>
                    <!-- END Block Tabs Justified Default Style -->
                </div>
            </div>
        </div>

        @include('postscheduling.modal.personal');
        @include('postscheduling.modal.gallery');

</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection