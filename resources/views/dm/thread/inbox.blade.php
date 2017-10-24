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
                            <ul class="block-options">
                                <li>
                                    <button type="button" data-toggle="block-option"
                                            data-action="fullscreen_toggle"></button>
                                </li>
                                <li>
                                    <button type="button" data-toggle="block-option" data-action="refresh_toggle"
                                            data-action-mode="demo"><i class="si si-refresh"></i></button>
                                </li>
                                <li>
                                    <button type="button" data-toggle="block-option"
                                            data-action="content_toggle"></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Friends</h3>
                        </div>
                        <div class="block-content">
                            <div class="pull-r-l pull-t push">
                                <table class="block-table text-center bg-gray-lighter border-b">
                                    <tbody>
                                    <tr>
                                        <td class="border-r" style="width: 50%;">
                                            <div class="h1 font-w700">3</div>
                                            <div class="h5 text-muted text-uppercase push-5-t">New Friends</div>
                                        </td>
                                        <td>
                                            <div class="push-30 push-30-t">
                                                <i class="si si-users fa-3x text-black-op"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="nav-users push">
                                <li>
                                    <a href="base_pages_profile.html">
                                        <img class="img-avatar" src="assets/img/avatars/avatar8.jpg" alt="">
                                        <i class="fa fa-circle text-success"></i> Megan Dean
                                        <div class="font-w400 text-muted">
                                            <small>Web Designer</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="base_pages_profile.html">
                                        <img class="img-avatar" src="assets/img/avatars/avatar14.jpg" alt="">
                                        <i class="fa fa-circle text-success"></i> Joshua Munoz
                                        <div class="font-w400 text-muted">
                                            <small>Graphic Designer</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="base_pages_profile.html">
                                        <img class="img-avatar" src="assets/img/avatars/avatar2.jpg" alt="">
                                        <i class="fa fa-circle text-success"></i> Amanda Powell
                                        <div class="font-w400 text-muted">
                                            <small>Photographer</small>
                                        </div>
                                    </a>
                                </li>
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