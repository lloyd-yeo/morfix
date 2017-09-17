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
                            <blockquote class='font-s12'>
                                <p>
                                    Instructions:
                                <ol>
                                    <li>Key in the <b>Morfix</b> email of the user to upgrade.</li>
                                    <li>Select the tier using the dropbox below.</li>
                                    <li>Click Upgrade & wait for confirmation.</li>
                                </ol>
                                </p>
                            </blockquote>
                            <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                        </div>

                        <form class="form-horizontal push-10" method="post" onsubmit="return false;">
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
                        <h3 class="block-title">Customer Stripe Details</h3>
                    </div>
                    <div class="block-content">
                        <div class="text-center push-10-t push-30">
                            <blockquote class='font-s12'>
                                <p>
                                    Instructions:
                                <ol>
                                    <li>Key in the <b>Morfix</b> email of the user to show stripe details for.</li>
                                    <li>Click show & wait, the details will be displayed below:</li>
                                </ol>
                                </p>
                            </blockquote>
                            <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                        </div>

                        <form class="form-horizontal push-10" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material">
                                        <input class="form-control" type="email" id="show-stripe-customer-id-email" name="show-stripe-customer-id-email" 
                                               placeholder="Enter user's email...">
                                        <label for="show-stripe-customer-id-email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button id='show-stripe-customer-id-btn' class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-address-book push-5-r"></i> Show Stripe Details</button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center push-10-t push-30">
                            <h3>Stripe IDs:</h3>
                            <ol id='show-stripe-customer-id-output-list'>
                                
                            </ol>
                        </div>
                        
                    </div>
                </div>
                <!-- END Material Lock -->
            </div>
        </div>
        
        <h2 class="content-heading">Auto Interactions</h2>
        <div class="row">
            <div class="col-lg-6">
                <!-- Material Lock -->
                <div class="block block-themed">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Manually activate Likes</h3>
                    </div>
                    <div class="block-content">
                        <div class="text-center push-10-t push-30">
                            <blockquote class='font-s12'>
                                <p>
                                    Instructions:
                                <ol>
                                    <li>Key in the <b>Morfix</b> email of the user to run Likes for manually.</li>
                                    <li>Click run & wait, the details will be displayed below:</li>
                                </ol>
                                </p>
                            </blockquote>
                            <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                        </div>

                        <form class="form-horizontal push-10" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material">
                                        <input class="form-control" type="email" id="interaction-like-email" name="interaction-like-email" 
                                               placeholder="Enter user's email...">
                                        <label for="interaction-like-email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button id='interaction-like-btn' class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-hourglass push-5-r"></i> Run</button>
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
                        <h3 class="block-title">Manually activate Comments</h3>
                    </div>
                    <div class="block-content">
                        <div class="text-center push-10-t push-30">
                            <blockquote class='font-s12'>
                                <p>
                                    Instructions:
                                <ol>
                                    <li>Key in the <b>Morfix</b> email of the user to run Comments for manually.</li>
                                    <li>Click run & wait, the details will be displayed below:</li>
                                </ol>
                                </p>
                            </blockquote>
                            <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                        </div>

                        <form class="form-horizontal push-10" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material">
                                        <input class="form-control" type="email" id="interaction-comment-email" name="interaction-comment-email" 
                                               placeholder="Enter user's email...">
                                        <label for="interaction-comment-email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button id='interaction-comment-btn' class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-hourglass push-5-r"></i> Run</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Material Lock -->
            </div>
        </div>
        <!-- Modules -->
        <div class="row">
             <div class="col-lg-6">
                <!-- Material Lock -->
                <div class="block block-themed">
                    <div class="block-header bg-danger">
                        <h3 class="block-title">Manually activate Follow</h3>
                    </div>
                    <div class="block-content">
                        <div class="text-center push-10-t push-30">
                            <blockquote class='font-s12'>
                                <p>
                                    Instructions:
                                <ol>
                                    <li>Key in the <b>Morfix</b> email of the user to run Follow for manually.</li>
                                    <li>Click run & wait, the details will be displayed below:</li>
                                </ol>
                                </p>
                            </blockquote>
                            <!--<img class="img-avatar img-avatar96" src="assets/img/avatars/avatar10.jpg" alt="">-->
                        </div>

                        <form class="form-horizontal push-10" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material">
                                        <input class="form-control" type="email" id="interaction-follow-email" name="interaction-follow-email" 
                                               placeholder="Enter user's email...">w
                                        <label for="interaction-follow-email">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button id='interaction-follow-btn' class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-hourglass push-5-r"></i> Run</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <!-- END Material Lock -->
            </div>           
        </div>
    </div>
    <!-- END Page Content -->
</main>
@endsection

@section('js')
@include('admin.js')
@endsection