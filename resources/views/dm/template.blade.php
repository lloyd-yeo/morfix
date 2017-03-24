@extends('layouts.app')

@section('css')
@include('dm.template.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'dm'])
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                <i class="si si-envelope"></i> DM Templates <small> Your conversation starter!</small>
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
                                <div class="form-material floating">
                                     @if (empty($ig_profile->insta_new_follower_template)) 
                                    <textarea class="form-control" id="greeting-template-txt" name="greeting-template" rows="7"></textarea>
                                    @else
                                    <textarea class="form-control" id="greeting-template-txt" name="greeting-template" rows="7">{{ $ig_profile->insta_new_follower_template }}</textarea>
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
                                <button class="btn btn-sm btn-primary" type="button" id="greeting-btn"><i class="fa fa-send push-5-r"></i> Save Template</button>
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
                    <form class="form-horizontal push-10">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material floating">
                                    @if (empty($ig_profile->follow_up_message)) 
                                    <textarea class="form-control" id="followup-template-txt" name="followup-template" rows="7"></textarea>
                                    @else
                                    <textarea class="form-control" id="followup-template-txt" name="followup-template" rows="7">{{ $ig_profile->follow_up_message }}</textarea>
                                    @endif
                                    <label for="followup-template">Follow-Up Message Template</label>
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
                                <label class="css-input switch switch-sm switch-success">
                                    <input type="checkbox" id="followup-delay-cbx" name="follow-up-delay"><span></span> Send follow-up messages a day after greeting message?
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-primary" type="button" id="followup-btn"><i class="fa fa-send push-5-r"></i> Save Template</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- END Sizes -->
        </div>
    </div>

</div>
@endforeach

@endsection

@section('js')
@include('dm.template.js')
@endsection