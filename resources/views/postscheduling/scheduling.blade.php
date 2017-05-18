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
                                                    <a class="btn btn-default upload-photo" data-image-id="{{ $user_img->id }}" href="javascript:void(0)"><i class="fa fa-pencil"></i> Schedule</a>
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
                        </div>
                    </div>
                    <!-- END Block Tabs Justified Default Style -->
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="modal fade" id="modalScheduleImage" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin modal-lg">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-modern">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title"><i class='fa fa-instagram'></i> SCHEDULE IMAGE</h3>
                        </div>
                    </div>
                    <div class="block-content">
                        <form class="form-horizontal push-10">
                            <span id="meta-id"></span>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        <textarea class="js-maxlength form-control" id="image-caption-txt" 
                                                  name="image-caption" rows="7" maxlength="2000"
                                                  placeholder="Type in the image caption here..."></textarea>
                                        <label for="image-caption">Your image caption</label>
                                    </div>
                                    <div class="help-block text-left">
                                        <b>EMOJI</b>
                                        <br/>
                                        Press ":" to bring up emojis.
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->user_tier > 1)
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        <textarea class="js-maxlength form-control" id="first-comment-txt" 
                                                  name="first-comment" rows="7" maxlength="2000"
                                                  placeholder="Type in the first comments here..."></textarea>
                                        <label for="first-comment">Your first comments</label>
                                    </div>
                                    <div class="help-block text-left">
                                        <b>EMOJI</b>
                                        <br/>
                                        Press ":" to bring up emojis.
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-sm btn-primary" type="button" id="greeting-btn" data-id="{{ $ig_profile->id }}"><i class="fa fa-instagram push-5-r"></i> Schedule Image</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
</main>
@endsection

@section('js')
@include('postscheduling.js')
@endsection