@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'admin'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="fa fa-question-circle-o"></i> Admin <small> Manage Morfix Settings here.</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        <!-- Modules -->
        <div class="block">
            <div class="block-content block-content-full block-content-narrow">
                <h2 class="content-heading">Lock Forms</h2>
                <div class="row">
                    <div class="col-lg-4">
                        <!-- Bootstrap Lock -->
                        <div class="block block-themed">
                            <div class="block-header bg-danger">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Bootstrap</h3>
                            </div>
                            <div class="block-content">
                                <div class="text-center push-10-t push-30">
                                    <img class="img-avatar img-avatar96" src="assets/img/avatars/avatar4.jpg" alt="">
                                </div>
                                <form class="form-horizontal" action="base_forms_premade.html" method="post" onsubmit="return false;">
                                    <div class="form-group">
                                        <label class="col-xs-12" for="lock1-password">Password</label>
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" id="lock1-password" name="lock1-password" placeholder="Enter your password..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-unlock push-5-r"></i> Unlock</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Bootstrap Lock -->
                    </div>
                    <div class="col-lg-4">
                        <!-- Material Lock -->
                        <div class="block block-themed">
                            <div class="block-header bg-danger">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Material</h3>
                            </div>
                            <div class="block-content">
                                <div class="text-center push-10-t push-30">
                                    <img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">
                                </div>
                                <form class="form-horizontal push-10" action="base_forms_premade.html" method="post" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material">
                                                <input class="form-control" type="password" id="lock2-password" name="lock2-password" placeholder="Enter your password..">
                                                <label for="lock2-password">Password</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-unlock push-5-r"></i> Unlock</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Material Lock -->
                    </div>
                    <div class="col-lg-4">
                        <!-- Material (floating) Lock -->
                        <div class="block block-themed">
                            <div class="block-header bg-danger">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Material (floating)</h3>
                            </div>
                            <div class="block-content">
                                <div class="text-center push-10-t push-30">
                                    <img class="img-avatar img-avatar96" src="assets/img/avatars/avatar1.jpg" alt="">
                                </div>
                                <form class="form-horizontal push-10" action="base_forms_premade.html" method="post" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material floating">
                                                <input class="form-control" type="password" id="lock3-password" name="lock3-password">
                                                <label for="lock3-password">Password</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-unlock push-5-r"></i> Unlock</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Material (floating) Lock -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Modules -->
    </div>
    <!-- END Page Content -->
</main>
@endsection