@extends('layouts.app')

@section('css')
@include('interactions.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'interaction'])
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                <i class="si si-rocket"></i> Interactions <small> Define WHO & HOW you want to engage</small>
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

        <div class="col-xs-12 col-lg-4">
            <!-- Sizes -->
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-cogs"></i> Auto Interaction Settings</h3>
                </div>

                <div class="block-content">
                    <div class="row items-push">
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                @if ($ig_profile->auto_like == 1)
                                <input class="toggle-like-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='fa fa-heart'></i> Like</span>
                                @else
                                <input class="toggle-like-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='fa fa-heart'></i> Like</span>
                                @endif
                            </label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                @if ($ig_profile->auto_comment == 1)
                                <input class="toggle-comment-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='fa fa-comments'></i> Comment</span>
                                @else
                                <input class="toggle-comment-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='fa fa-comments'></i> Comment</span>
                                @endif
                            </label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                @if ($ig_profile->auto_follow == 1)
                                <input class="toggle-follow-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='si si-user-follow'></i> Follow</span>
                                @else
                                <input class="toggle-follow-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='si si-user-follow'></i> Follow</span>
                                @endif
                            </label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                @if ($ig_profile->auto_follow == 1)
                                <input class="toggle-unfollow-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='si si-user-unfollow'></i> Unfollow</span>
                                @else
                                <input class="toggle-unfollow-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='si si-user-unfollow'></i> Unfollow</span>
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Sizes -->
        </div>

        <div class="col-xs-12 col-lg-8">
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-bullseye"></i> Niche Targeting</h3>
                </div>
                <div class="block-content">
                    <div class="row items-push">
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-lg-12">
                                            <div class="form-material">
                                                <select class="js-select2 form-control toggle-niche" data-id="{{ $ig_profile->id }}" name="niche" style="width: 100%;" data-placeholder="Choose a niche..">
                                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                                    @foreach ($niches as $niche)
                                                    @if ($ig_profile->niche == $niche->niche_id)
                                                    <option value='{{ $niche->niche_id }}' selected >{{ $niche->niche }}</option>
                                                    @else
                                                    <option value='{{ $niche->niche_id }}'>{{ $niche->niche }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <label for="niche-target">Niche Targeting</label>
                                            </div>
                                            <div class="alert alert-info alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <!--<h3 class="font-w300 push-15"></h3>-->
                                                <p>Simply select a <a class="alert-link" href="javascript:void(0)">niche</a> and let Morfix handle the targeting for you!</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-cogs"></i> Advanced Auto Follow/Unfollow Settings</h3>
                </div>
                
                <div class="block-content">
                    
                    <div class="row items-push col-lg-12">
                        <form class="col-lg-12 col-xs-12">
                            <div class="form-group">
                                <div class='col-xs-12 col-lg-12'>
                                    <div class="form-material floating">
                                        <label class="css-input css-input switch switch-square switch-primary">
                                            <input type="checkbox" checked><span></span> <span style='font-weight: 600;'>Unfollow users that don't follow me</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="row items-push col-lg-12">
                        <form class="js-validation-material col-lg-12 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12 col-lg-12">
                                    <div class="form-material floating">
                                        <input class="form-control" type="text" id="val-digits2" name="val-digits2">
                                        <label for="val-digits2">Maximum Followers Filter</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 col-lg-12">
                                    <div class="form-material floating">
                                        <input class="form-control" type="text" id="material-gridf3" name="val-digits2">
                                        <label for="material-gridf3"><i class=""></i> Minimum Followers Filter</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="block-header bg-info">
                    <h3 class="block-title text-white text-uppercase"><i class="fa fa-comments"></i> My Comments</h3>
                </div>
                <div class="block-content">
                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material">
                                                <input class="js-tags-input form-control" type="text" id="comment-tags" 
                                                       name="comments" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('js')
@include('interactions.js')
@endsection