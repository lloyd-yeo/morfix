@extends('layouts.app')

@section('css')
    @include('dm.log.css')
@endsection

@section('sidebar')
    @include('sidebar', ['page' => 'dmthread'])
@endsection

@section('content')
    <main id="main-container">

        <div class="content bg-gray-lighter">
            <div class="row items-push">
                <div class="col-sm-7">
                    <h1 class="page-heading">
                        <i class="si si-envelope"></i> Direct Message Inbox
                        <small> View your Insta-DM Inbox!</small>
                    </h1>
                </div>
            </div>
        </div>

        <div class="content content-boxed">
            <div class="row font-s13">
                <div class="col-lg-4">
                    <!-- Friends Widget -->
                    <div class="block block-bordered">
                        <div class="block-header">
                            <h3 class="block-title"><i></i>DM Inbox</h3>
                        </div>
                        <div class="block-content">
                            <ul class="nav-users push">
                                @include('dm.thread.thread')
                                @include('dm.thread.thread')
                                @include('dm.thread.thread')
                            </ul>
                        </div>
                    </div>
                    <!-- END Friends Widget -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section('js')
    @include('dm.log.js')
@endsection