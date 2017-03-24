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
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="form-material floating">
                                <textarea class="form-control" id="greeting-template" name="greeting-template" rows="7"></textarea>
                                <label for="greeting-template">Template</label>
                            </div>
                            <div class="help-block text-right">Feel free to use common tags: &lt;blockquote&gt;, &lt;strong&gt;, &lt;em&gt;</div>
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
    @include('dm.template.js')
@endsection