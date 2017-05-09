@extends('layouts.app')

@section('sidebar')
@include('sidebar', ['page' => 'affiliate'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-trophy"></i> Affiliate <small> View your referrals & commisions here.</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        
        <!-- Header Tiles -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="h1 font-w700 text-modern" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission_payable }}"></div>
                    </div>
                    <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Pending Commission this Month</div>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full text-modern">
                        <div class="h1 font-w700" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></div>
                    </div>
                    <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Total Pending Commission</div>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full text-modern">
                        <div class="h1 font-w700" data-subject="money" data-toggle="countTo"
                            data-decimals="2" data-to="{{ Auth::user()->all_time_commission }}">
                            $ {{ number_format(Auth::user()->all_time_commission, 2, '.', ',')  }}
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Total Commission</div>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a class="block block-link-hover3 text-center" href="javascript:void(0)">
                    <div class="block-content block-content-full text-modern">
                        <div class="h1 font-w700" data-toggle="countTo" data-to="{{ count($active_users) }}">{{ count($active_users) }}</div>
                    </div>
                    <div class="block-content block-content-full block-content-mini bg-gray-lighter text-muted font-w600">Active Affiliates</div>
                </a>
            </div>
        </div>
        <!-- END Header Tiles -->
        
        
        <div class="row font-s13">
            <div class="block" id="affiliate-link-block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-link"></i> Referral Links</h2>
                </div>
                <div class="block-content block-content-full block-content-narrow">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4 class="font-w300 push-15"><i class="fa fa-info-circle"></i> 
                                Use the links below to promote MorfiX!<br/>
                                Our system will track your referrals in real-time and track your affiliate commissions earned. 
                            </h4>
                            <p>You automatically <b>EARN</b> when users sign up using your affiliate link and upgrade to any of our premium plans!</p>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Name</th>
                                <th class="hidden-xs" style="width: 15%;">Access</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>Craig Stone</td>
                                <td class="hidden-xs">
                                    <span class="label label-danger">Disabled</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>Sara Holland</td>
                                <td class="hidden-xs">
                                    <span class="label label-primary">Personal</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Ethan Howard</td>
                                <td class="hidden-xs">
                                    <span class="label label-info">Business</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td>Helen Silva</td>
                                <td class="hidden-xs">
                                    <span class="label label-danger">Disabled</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">5</td>
                                <td>Vincent Sims</td>
                                <td class="hidden-xs">
                                    <span class="label label-info">Business</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">6</td>
                                <td>Megan Dean</td>
                                <td class="hidden-xs">
                                    <span class="label label-primary">Personal</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Edit Client"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="" data-original-title="Remove Client"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
@include('dashboard.js')
@endsection