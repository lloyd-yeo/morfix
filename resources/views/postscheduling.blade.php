@extends('layouts.app')

@section('sidebar')
    @include('sidebar', ['page' => 'postscheduling'])
@endsection

@section('content')
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">
                <i class="si si-rocket"></i> Post Scheduling <small> Schedule post automatically for posting.</small>
            </h1>
        </div>
    </div>
</div>

<div class="content content-boxed">
    <div class="row font-s13">
        @foreach ($user_ig_profiles as $ig_profile)
        <div class="col-lg-4">
            <!-- Add Friend -->
            <div class="bg-image" style="background-image: url('{{ $ig_profile->profile_pic_url }}');">
                <div class="bg-black-op">
                    <div class="block block-themed block-transparent">
                        <div class="block-header">
                            <h3 class="block-title text-center"><i class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}</h3>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <div class="push">
                                <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ $ig_profile->profile_pic_url }}" alt="">
                            </div>
                            <h3 class="h1 font-w300 text-white">{{ $ig_profile->profile_full_name }}</h3>
                        </div>
                        <div class="block-content block-content-full text-center">
                            <a class="btn btn-sm btn-default" href="/post-scheduling/schedule/{{ $ig_profile->id }}">
                                <i class="fa fa-fw fa-instagram"></i> Schedule Posts
                            </a>
                            <a class="btn btn-sm btn-default" href="/post-scheduling/logs/{{ $ig_profile->id }}">
                                <i class="fa fa-fw fa-eye"></i> View Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Add Friend -->
        </div>
        @endforeach
    </div>
</div>
@endsection