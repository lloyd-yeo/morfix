@extends('layouts.app')

@section('css')
@include('affiliate.css')
@endsection

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
                <div class="block-content block-content-full">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissable" style="font-size: 16px;">
                            <p class="font-w300 push-15"><i class="fa fa-info-circle"></i> 
                                Use the links below to promote MorfiX!<br/>
                                Our system will track your referrals in real-time and track your affiliate commissions earned. 
                            </p>
                            <p>You automatically <b>EARN</b> when users sign up using your affiliate link and upgrade to any of our premium plans!</p>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="" style="width: 65%;"><i class="fa fa-filter"></i> Funnel</th>
                                <th class="" style="width: 35%;"><i class="fa fa-link"></i> Referral Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referral_links as $referral_link)
                            <tr>
                                <td>{{ $referral_link->title }}</td>
                                <td><a href="http://morfix.co/r/{{ $referral_link->keyword }}">http://morfix.co/r/{{ $referral_link->keyword }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row font-s13">
            <div class="col-lg-12">
                <div class="alert alert-success alert-dismissable">
                    <p class="font-w500 push-15" style="font-size: 24px;"><i class="fa fa-thumbs-up"></i> 
                        Use your affiliate links at the top of this page to promote MorfiX and start earning!
                    </p>
                </div>
            </div>
        </div>

        <div class="row font-s13">
            <div class="block" id="affiliate-link-block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-handshake-o"></i> Affiliate Payment Terms</h2>
                </div>
                <div class="block-content block-content-full">
                    @include('affiliate.terms');
                </div>
            </div>
        </div>


        <div class="row font-s13">
            <div class="block" id="affiliate-link-block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-paypal"></i> Paypal Email</h2>
                </div>
                <div class="block-content block-content-full">
                    <form class="form-horizontal" action="base_forms_premade.html" method="post" onsubmit="return false;">
                        <div class="form-group">
                            <label class="col-xs-12" for="paypal-email">Paypal Email</label>
                            <div class="col-xs-12 form-material form-material-success">
                                <input class="form-control" type="email" id="paypal-email" name="paypal-email" placeholder="Enter your paypal email..">
                                <div class="help-block text-left">Enter your paypal email to receive your payout!</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-success" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row font-s13">
            <div class="block" id="affiliate-link-block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-paypal"></i> Paypal Email</h2>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th class="hidden-xs">User Tier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $key => $referral)
                            @if ($key%2 == 0)
                            <tr role="row" class="even">
                                @else
                            <tr role="row" class="odd">
                                @endif
                                <td class="font-w600">{{ $referral->email }}</td>
                                @if ($referral->user_tier == 2)
                                <td>Premium</td>
                                @elseif ($referral->user_tier == 3)
                                <td>Pro</td>
                                @elseif ($referral->user_tier == 4)
                                <td>Business</td>
                                @elseif ($referral->user_tier == 5)
                                <td>Mastermind</td>
                                @endif
                            </tr>
                            @endforeach
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