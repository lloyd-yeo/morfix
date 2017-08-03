@extends('layouts.app')

@section('css')
@include('interactions.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'interaction'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-rocket"></i> Interactions <small> Define WHO & HOW you want to engage</small>
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
            
            <div class="col-xs-12 col-lg-4">
                <!-- Sizes -->
                <div class="block" style='height: 300px;'>
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-bar-chart"></i> Statistics</h3>
                    </div>
                    
                    <div class="block-content" id="auto-interaction-settings-container">
                        <div class="row items-push">
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    <span style='font-size: 15px; font-weight: 600;'><i class='fa fa-2x fa-heart text-danger'></i> {{ $likes_done }} Like <span class="text-success">(<i class="fa fa-arrow-up"></i> {{ $likes_done_today }})</span></span>
                                </label>
                            </div>
                            @if (Auth::user()->tier > 1)
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    <span style='font-size: 15px; font-weight: 600;'><i class='fa fa-2x fa-comments text-primary'></i> {{ $comments_done }} Comment <span class="text-success">(<i class="fa fa-arrow-up"></i> {{ $comments_done_today }})</span></span>
                                </label>
                            </div>
                            @else
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input css-input-disabled switch switch-square switch-lg switch-primary">
                                    <span style='font-size: 15px; font-weight: 600;'><i class='fa fa-2x fa-comments text-primary'></i> {{ $comments_done }} Comment <span class="text-success">(<i class="fa fa-arrow-up"></i> {{ $comments_done_today }})</span></span>
                                </label>
                            </div>
                            @endif
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    <span style='font-size: 15px; font-weight: 600;'><i class='si fa-2x si-user-follow text-modern'></i> {{ $follows_done }} Follow <span class="text-success">(<i class="fa fa-arrow-up"></i> {{ $follows_done_today }})</span></span>
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    <span style='font-size: 15px; font-weight: 600;'><i class='si fa-2x si-user-unfollow text-lightred'></i> {{ $unfollows_done }} Unfollow <span class="text-success">(<i class="fa fa-arrow-up"></i> {{ $unfollows_done_today }})</span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Sizes -->
            </div>
            
            <div class="col-xs-12 col-lg-4">
                <!-- Sizes -->
                <div class="block" style='height: 300px;'>
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-cogs"></i> Settings</h3>
                    </div>

                    <div class="block-content" id="auto-interaction-settings-container">
                        <div class="row items-push">
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    @if ($ig_profile->auto_like == 1)
                                    <input class="toggle-like-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='fa fa-2x fa-heart text-danger'></i> Like</span>
                                    <i class="fa fa-spin fa-refresh text-modern" id="like-spinner"></i>
                                    @else
                                    <input class="toggle-like-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='fa fa-2x fa-heart text-danger'></i> Like</span>
                                    <i class="fa fa-refresh text-modern" id="like-spinner"></i>
                                    @endif
                                    
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    @if ($ig_profile->auto_comment == 1)
                                    <input class="toggle-comment-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='fa fa-2x fa-comments text-primary'></i> Comment</span>
                                    <i class="fa fa-spin fa-refresh text-modern" id="comment-spinner"></i>
                                    @else
                                    <input class="toggle-comment-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='fa fa-2x fa-comments text-primary'></i> Comment</span>
                                    <i class="fa fa-refresh text-modern" id="comment-spinner"></i>
                                    @endif
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    @if ($ig_profile->auto_follow == 1)
                                    <input class="toggle-follow-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='si fa-2x si-user-follow text-modern'></i> Follow</span>
                                    <i class="fa fa-spin fa-refresh text-modern" id="follow-spinner"></i>
                                    @else
                                    <input class="toggle-follow-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='si fa-2x si-user-follow text-modern'></i> Follow</span>
                                    <i class="fa fa-refresh text-modern" id="follow-spinner"></i>
                                    @endif
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-12">
                                <label class="css-input css-input switch switch-square switch-lg switch-primary">
                                    @if ($ig_profile->auto_unfollow == 1)
                                    <input class="toggle-unfollow-btn" data-id="{{ $ig_profile->id }}" type="checkbox" checked><span></span> <span style='font-weight: 600;'><i class='si fa-2x si-user-unfollow text-lightred'></i> Unfollow</span>
                                    <i class="fa fa-spin fa-refresh text-modern" id="unfollow-spinner"></i>
                                    @else
                                    <input class="toggle-unfollow-btn" data-id="{{ $ig_profile->id }}" type="checkbox"><span></span> <span style='font-weight: 600;'><i class='si fa-2x si-user-unfollow text-lightred'></i> Unfollow</span>
                                    <i class="fa fa-refresh text-modern" id="unfollow-spinner"></i>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Sizes -->
            </div>
            
            <div class="col-xs-12 col-lg-4">
                <div class="block" style='height: 300px;'>
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-crosshairs"></i> Niche Targeting</h3>
                    </div>
                    <div class="block-content" id="niche-targeting-container">
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
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-forward"></i> Advanced Auto Follow/Unfollow Settings</h3>
                    </div>
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-lg-12">
                                <form class="js-validation-material form-horizontal push-10-t" id="advanced-follow-settings-form" data-id="{{ $ig_profile->id }}">
                                    <div class="form-group">
                                        <div class='col-xs-12 col-lg-10 col-lg-offset-1'>
                                            <div class="form-material">
                                                <label class="css-input css-input switch switch-square switch-primary">
                                                    @if ($ig_profile->unfollow_unfollowed == 1)
                                                    <input type="checkbox" name="unfollow-toggle" checked><span></span> 
                                                    <span style='font-weight: 600;'>Only Unfollow users that didn't follow me back </span>
                                                    <small><i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                                                            data-placement="top" title="Skip unfollowing users that have followed you back" type="button"></i></small>
                                                    @else
                                                    <input type="checkbox" name="unfollow-toggle"><span></span> 
                                                    <span style='font-weight: 600;'>Only Unfollow users that didn't follow me back</span>
                                                    <small><i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                                                            data-placement="top" title="Skip unfollowing users that have followed you back" type="button"></i></small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group push-30-t">
                                        <div class='col-xs-12 col-lg-10 col-lg-offset-1'>
                                            <div class="form-material">
                                                <label class="css-input css-input switch switch-square switch-primary">
                                                    @if ($ig_profile->follow_recent_engaged == 1)
                                                    <input type="checkbox" name="recent-follower-toggle" checked><span></span> 
                                                    <span style='font-weight: 600;'>Follow Likers & Commenters of 3 most recent posts</span>
                                                    <small><i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                                                            data-placement="top" title="Target people that has engaged with your competitor's 3 most recent posts instead of their followers." type="button"></i></small>
                                                    @else
                                                    <input type="checkbox" name="recent-follower-toggle"><span></span> 
                                                    <span style='font-weight: 600;'>Follow Likers & Commenters of 3 most recent posts</span>
                                                    <small><i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                                                            data-placement="top" title="Engage people that has engaged with your competitor's 3 most recent posts instead of their followers." type="button"></i></small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group push-30-t">
                                        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                                            <div class="form-material form-material-primary">
                                                <input class="form-control" type="text" id="min-follower-filter" name="min-follower-filter" value="{{ $ig_profile->follow_min_followers }}">
                                                <label for="min-follower-filter">Minimum Followers Filter</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                                            <div class="form-material form-material-primary">
                                                <input class="form-control" type="text" id="max-follower-filter" name="max-follower-filter" value="{{ $ig_profile->follow_max_followers }}">
                                                <label for="max-follower-filter"><i class=""></i> Maximum Followers Filter</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                                            <div class="form-material form-material-primary">
                                                <input class="form-control" type="text" id="follow-cycle" name="follow-cycle" value="{{ $ig_profile->follow_cycle }}">
                                                <label for="follow-cycle"><i class=""></i> Follow/Unfollow Cycle</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-10 col-lg-10 col-lg-offset-1" for="follow-speed-select">Follow/Unfollow Speed</label>
                                        <div class="col-sm-12 col-lg-10 col-lg-offset-1">
                                            <select class="form-control" id="follow-speed-select" name="follow-speed" size="1">
                                                @if ($ig_profile->speed == "Medium")
                                                <option value="Medium" selected>Medium</option>
                                                @else
                                                <option value="Medium">Medium</option>
                                                @endif
                                                @if ($ig_profile->speed == "Fast")
                                                <option value="Fast" selected>Fast</option>
                                                @else
                                                <option value="Fast">Fast</option>
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                                            <button class="btn btn-sm btn-primary" type="submit" id="advanced-follow-settings-btn"><i class="fa fa-check push-5-r"></i> Save Advanced Settings</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="block">
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-comments"></i> My Comments</h3>
                    </div>
                    <div class="block-content" id="my-comments-block" style="height: 370px;">
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div id="comments-well" class="block">
                                    <div class="block-content bg-gray-light block-content-full" style="height: 240px;">
                                        <!-- SlimScroll Container -->
                                        <div id="comments-well-inner-container" data-toggle="slimscroll" data-color="#568bce" data-always-visible="true">
                                            <p class="nice-copy" id="comments-well-inner">
                                                @foreach ($user_ig_comments as $comment)
                                                <button id="comment-{{ $comment->comment_id }}" class="btn btn-primary btn-sm btn-rounded remove-comment-btn push-5-r push-10" type="button" data-id="{{ $comment->comment_id }}">{{ $comment->comment }}<i class="fa fa-times"></i> </button>
                                                @endforeach
                                            </p>
                                        </div>
                                        <!-- END SlimScroll Container -->
                                    </div>
                                </div>
                                <form class="form-horizontal" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" id="comments-text" 
                                                       name="comment-tag" placeholder="Enter your comments here...">
                                                <span class="input-group-btn">
                                                    <button id="add-comment-btn" class="btn btn-default" type="button" data-id="{{ $ig_profile->id }}"><i class="fa fa-plus"></i></button>
                                                </span>
                                            </div>
                                            <div class="help-block text-left" style="color: #70b9eb;">
                                                To input emojis just press the ":" button while adding your comments.
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="block">
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-users"></i> Target Usernames</h3>
                    </div>
                    <div class="block-content" id="my-users-block" style="height: 380px;">
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div id="users-well" class="block">
                                    <div class="block-content bg-gray-light block-content-full" style="height: 240px;">
                                        <!-- SlimScroll Container -->
                                        <div id="users-well-inner" data-toggle="slimscroll" data-color="#568bce" data-always-visible="true">
                                            @foreach ($user_ig_target_usernames as $target_username)
                                            <button id="username-{{ $target_username->target_id }}" class="btn 
                                                    @if ($target_username->invalid == 1 || $target_username->insufficient_followers == 1)
                                                    btn-danger
                                                    @else
                                                    btn-primary
                                                    @endif 
                                                    btn-sm btn-rounded remove-username-btn push-5-r push-10" type="button" data-id="{{ $target_username->target_id }}">{{ $target_username->target_username }}<i class="fa fa-times"></i> </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <form class="form-horizontal" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" id="users-text" 
                                                       name="users-tag" placeholder="Enter your target username here...">
                                                <span class="input-group-btn">
                                                    <button id="add-username-btn" class="btn btn-default" type="button" data-id="{{ $ig_profile->id }}"><i class="fa fa-plus"></i></button>
                                                </span>
                                            </div>
                                            <div class="help-block text-left" style="color:#d26a5c;">
                                                When entering usernames make sure to exclude "@" & also ensure that the username is valid.
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="block">
                    <div class="block-header bg-primary">
                        <h3 class="block-title text-white text-uppercase"><i class="fa fa-hashtag"></i> Target Hashtags</h3>
                    </div>
                    <div class="block-content" id="my-hashtags-block" style="height: 380px;">
                        <div class='row'>
                            <div class='col-lg-12'>
                                <div id="hashtags-well" class="block">
                                    <div class="block-content bg-gray-light block-content-full" style="height: 240px;">
                                        <!-- SlimScroll Container -->
                                        <div data-toggle="slimscroll" data-color="#568bce" data-always-visible="true">
                                            <p class="nice-copy" id="hashtags-well-inner">
                                                @foreach ($user_ig_target_hashtags as $target_hashtag)
                                                <button id="hashtag-{{ $target_hashtag->id }}" class="btn btn-primary btn-sm btn-rounded remove-hashtag-btn push-5-r push-10" type="button" data-id="{{ $target_hashtag->id }}">{{ $target_hashtag->hashtag }}<i class="fa fa-times"></i> </button>
                                                @endforeach
                                            </p>
                                        </div>
                                        <!-- END SlimScroll Container -->
                                    </div>
                                </div>
                                <form class="form-horizontal" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" id="hashtags-text" 
                                                       name="hashtags-tag" placeholder="Enter your target hashtag here...">
                                                <span class="input-group-btn">
                                                    <button id="add-hashtag-btn" class="btn btn-default" type="button" data-id="{{ $ig_profile->id }}"><i class="fa fa-plus"></i></button>
                                                </span>
                                            </div>
                                            <div class="help-block text-left" style="color:#d26a5c;">
                                                When entering hashtags make sure to exclude "#" & also ensure that the hashtag has good traffic.
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script type="text/javascript">
            var $autoInteractionSettingsHeight = $("#auto-interaction-settings-container").height();
            $autoInteractionSettingsHeight = $autoInteractionSettingsHeight + 20;
            $("#niche-targeting-container").css("height", $autoInteractionSettingsHeight + "px");

            var $myCommentBoxHeight = $("#my-comments-block").height();
            var $commentsWellHeight = $("#comments-well").height();
            var $commentBoxHeight = $("#comments-text").height();
            var $commentsBlockHeight = $myCommentBoxHeight - $commentBoxHeight - 20 - 20 - 20 - 30;
            $("#comments-well").css("height", $commentsBlockHeight + "px");
            $("#comments-well-inner-container").attr("data-height", ($commentsBlockHeight - 20 - 20) + "px");

            var $myTargetedUsernameBoxHeight = $("#my-users-block").height();
            var $usernameWellHeight = $("#users-well").height();
            var $usernameBoxHeight = $("#users-text").height();
            var $usernameBlockHeight = $myTargetedUsernameBoxHeight - $usernameBoxHeight - 20 - 20 - 20 - 40;
            $("#users-well").css("height", $usernameBlockHeight + "px");

            var $myTargetedHashtagsBoxHeight = $("#my-hashtags-block").height();
            var $hashtagsWellHeight = $("#hashtags-well").height();
            var $hashtagsBoxHeight = $("#hashtags-text").height();
            var $hashtagsBlockHeight = $myTargetedHashtagsBoxHeight - $hashtagsBoxHeight - 20 - 20 - 20 -40;
            $("#hashtags-well").css("height", $hashtagsBlockHeight + "px");
        </script>
    </div>
</main>
@endsection

@section('js')
@include('interactions.js')
@endsection