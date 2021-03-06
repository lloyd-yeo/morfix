@extends('layouts.app')

@section('css')
@include('dm.template.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'dm'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-envelope"></i>  Auto Direct-Message Templates <small> Your conversation starter!</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="content content-narrow">

        <div class="row">
            <div class="col-xs-12 col-lg-12">
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
                                    <div class="help-block text-left">
                                        <b>PLACEHOLDER</b>
                                        <br/>
                                        ${full_name} - Type this as a placeholder for the recipient's profile's full name.
                                        <br/>
                                        ${Hey|Hi|Hello} - Enclose your words in a curly bracket so the system will randomly select a word to use.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    @if ($ig_profile->auto_dm_new_follower == 0)
                                    <label class="css-input switch switch-sm switch-success">
                                        <input type="checkbox" id="dm-cbx" name="auto-dm" data-id="{{ $ig_profile->id }}"><span></span> Turn on Auto Direct Message?
                                    </label>
                                    @elseif ($ig_profile->auto_dm_new_follower == 1)
                                    <label class="css-input switch switch-sm switch-success">
                                        <input type="checkbox" id="dm-cbx" name="auto-dm" data-id="{{ $ig_profile->id }}" checked><span></span> Turn on Auto Direct Message?
                                    </label>
                                    @endif
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

            @if (Auth::user()->tier > 3)
            <div class="col-xs-12 col-lg-12">
                <!-- Sizes -->
                <div class="block" id='follow-up-block'>
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-envelope"></i> FOLLOW-UP MESSAGE TEMPLATE</h3>
                    </div>

                    <div class="block-content">
                        <form class="form-horizontal push-10">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        @if (empty($ig_profile->follow_up_message)) 
                                        <textarea class="form-control" id="followup-template-txt" name="followup-template" rows="7" placeholder="Type in your follow-up message template here..."></textarea>
                                        @else
                                        <textarea class="form-control" id="followup-template-txt" name="followup-template" rows="7" placeholder="Type in your follow-up messagetemplate here...">{{ $ig_profile->follow_up_message }}</textarea>
                                        @endif
                                        <label for="followup-template">Follow-Up Message Template</label>
                                    </div>
                                    <div class="help-block text-left">
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
                                    @if ($ig_profile->auto_dm_delay == 0)
                                    <label class="css-input switch switch-sm switch-success">
                                        <input type="checkbox" id="followup-delay-cbx" name="follow-up-delay" data-id="{{ $ig_profile->id }}"><span></span> Send follow-up messages a day after greeting message?
                                    </label>
                                    @else
                                    <label class="css-input switch switch-sm switch-success">
                                        <input type="checkbox" id="followup-delay-cbx" name="follow-up-delay" data-id="{{ $ig_profile->id }}" checked><span></span> Send follow-up messages a day after greeting message?
                                    </label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-sm btn-primary" type="button" id="followup-btn" data-id="{{ $ig_profile->id }}"><i class="fa fa-send push-5-r"></i> Save Template</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- END Sizes -->
            </div>
            @else
            <div class="col-xs-12 col-lg-12">
                <a class="block block-link-hover2" href="#" data-toggle="modal" data-target="#upgrade-dm-modal">
                    <div class="block-content block-content-full bg-primary clearfix">
                        <i class="fa fa-unlock fa-2x text-white pull-left"></i>
                        <span class="h4 font-w700 text-white">Unlock</span> <span class="h4 text-white-op">follow-up messaging</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

@section('js')
@include('dm.template.js')
@endsection