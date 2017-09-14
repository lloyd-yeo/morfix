@extends('layouts.app')

@section('css')
@include('interactions.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'admin'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="fa fa-wrench"></i> Admin <small> Manage Morfix Settings here.</small>
                </h1>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content content-boxed">
        <!-- Modules -->
        <div class="block">
            <div class="block-content block-content-full block-content-narrow">
                <h2 class="content-heading">User Administration</h2>
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Material Lock -->
                        <div class="block block-themed">
                            <div id="upgrade-tier-block" class="block-header bg-danger">
                                <h3 class="block-title">Upgrade User Tier</h3>
                            </div>
                            <div class="block-content">
                                <div class="text-center push-10-t push-30">
                                    <blockquote>
                                        <p>Instructions:</p>
                                        <p>1. Key in the <b>Morfix</b> email of the user to upgrade.</p>
                                        <p>2. Select the tier using the dropbox below.</p>
                                        <p>3. Click Upgrade & wait for confirmation.</p>
                                    </blockquote>
                                    <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                                </div>
                                <form class="form-horizontal push-10" action="base_forms_premade.html" method="post" onsubmit="return false;">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material">
                                                <input class="form-control" type="email" id="upgrade-tier-email" name="upgrade-tier-email" 
                                                       placeholder="Enter user's email...">
                                                <label for="upgrade-tier-email">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material">
                                                <select class="form-control" id="upgrade-tier" name="upgrade-tier" size="1">
                                                    <option value="2">Premium</option>
                                                    <option value="12">Business + Premium</option>
                                                    <option value="3">Pro</option>
                                                    <option value="13">Business + Pro</option>
                                                </select>
                                                <label for="upgrade-tier">Tier?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button id='upgrade-tier-btn' class="btn btn-sm btn-danger" type="submit">
                                                <i class="fa fa-angle-double-up push-5-r"></i> Upgrade</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- END Material Lock -->
                    </div>
                    <div class="col-lg-6">
                        <!-- Material Lock -->
                        <div class="block block-themed">
                            <div class="block-header bg-danger">
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
                    <div class="col-lg-6">
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
                </div>
            </div>
        </div>
        <!-- Modules -->
    </div>
    <!-- END Page Content -->
</main>
@endsection

@section('js')
@include('admin.js')
@endsection