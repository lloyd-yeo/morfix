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
                        @if (Auth::user()->email == "gabriel@instaffiliates.com")
                        <div class="h1 font-w700" data-toggle="countTo" data-to="64">64</div>
                        @else
                        <div class="h1 font-w700" data-toggle="countTo" data-to="{{ count($referrals) }}">{{ count($referrals) }}</div>
                        @endif
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
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="si si-users"></i> Refer-a-Friend!</h2>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referral_links as $referral_link)
                            <tr>
                                <td>{{ $referral_link->title }}</td>
                                <td><a href="http://morfix.co/r/{{ $referral_link->keyword }}">https://morfix.co/r/{{ $referral_link->keyword }}</a></td>
                                <td><button class="btn btn-sm btn-info add-pixel-btn" 
                                            type="submit" data-keyword="{{ $referral_link->keyword }}">Add Pixel</button></td>
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
            <div class="block" id="affiliate-link-block" style="overflow-y: auto;">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-handshake-o"></i> Affiliate Payment Terms</h2>
                </div>
                <div class="block-content block-content-full push-20">
                    @include('affiliate.terms')
                </div>
            </div>
        </div>


        <div class="row font-s13">
            <div class="block" id="affiliate-link-block-paypal">
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
                                @if (Auth::user()->paypal_email === NULL || Auth::user()->paypal_email == "")
                                <input class="form-control" type="email" data-id="{{ Auth::user()->user_id }}" id="paypal-email" name="paypal-email" placeholder="Enter your paypal email..">
                                @else
                                <input class="form-control" value="{{ Auth::user()->paypal_email }}" type="email" data-id="{{ Auth::user()->user_id }}" id="paypal-email" name="paypal-email" placeholder="Enter your paypal email..">
                                @endif
                                <div class="help-block text-left">Enter your paypal email to receive your payout!</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button class="btn btn-sm btn-success btn-save-paypal-email" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row font-s13">
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-group"></i> Referrals</h2>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th class="hidden-xs">User Tier</th>
                                <th>Join Date</th>
                                <th>Payment Source</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $key => $referral)
                            @if ($referral->tier > 1)
                                @if ($key%2 == 0)
                                <tr role="row" class="even">
                                    @else
                                <tr role="row" class="odd">
                                    @endif
                                    <td class="font-w600">{{ $referral->email }}</td>
                                    @if ($referral->tier == 2)
                                    <td>Premium</td>
                                    @elseif ($referral->tier == 3)
                                    <td>Pro</td>
                                    @elseif ($referral->tier == 12)
                                    <td>Business</td>
                                    @elseif ($referral->tier == 22)
                                    <td>Mastermind</td>
                                    @elseif ($referral->tier == 23)
                                    <td>Mastermind</td>
                                    @endif
                                    <td>{{ $referral->created_at }}</td>
                                    @if ($referral->paypal === 1)
                                    <td><img style="height: 20px;" src="{{ asset('assets/img/logo/credit-card.png') }}" /></td>
                                    @else
                                    <td><img style="height: 20px;" src="{{ asset('assets/img/logo/paypal-logo.png') }}" /></td>
                                    @endif
                                </tr>
                            @endif
                            @endforeach
                            
                            @if (Auth::user()->email == "gabriel@instaffiliates.com")
                            <tr role="row" class="even">
                                <td class="font-w600">joel@onlinesalespro.com</td>
                                <td>Mastermind</td>
                                <td>2017-04-26 08:04:07</td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="font-w600">Shawnjosiah.pd@gmail.com</td>
                                <td>Mastermind</td>
                                <td>2017-04-26 08:04:07</td>
                            </tr>
                            <tr role="row" class="even">
                                <td class="font-w600">albertjames24mb@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-04-26 08:04:07</td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="font-w600">sl@buildsocialprofits.com</td>
                                <td>Premium</td>
                                <td>2017-04-26 08:04:07</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zwaccount@live.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zumbagirl1991@yahoo.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zuhair.tahan@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zoe.vanhayden@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zloster89@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zkaylintha@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zisizit@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">ziliana97@hotmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zhiyunlim99@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zhengting2001@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zhangyixin.911@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zettabarcus@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">Zestliving01@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zenprofits4cindy@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zayd.boon@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zavierlimjj@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zarqasaif@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zariuschan1993@hotmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">Zari04815@yahoo.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zakiarahmalia19@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zainuddin0480@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zainabjacobs.z@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zahi_deeb.4@hotmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zahidul_islam31@yahoo.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zackrahim46@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">zackquilici@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">zackmichaelofficial@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Zachly25@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yvimaria@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">yusmaniar06@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">Yuesheng7296@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Youssef.sabbahi1019@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">younessouazri@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">you@norahelton.com.co</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yostachu@wp.pl</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">Yongshaokoko@gmail.com</td>
                                <td>Pro</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yohansanjeewa0@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">yohancemcgruder@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yogiinthelight@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">yob.fxe.rmondragon@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yma_hopson@yahoo.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">yendezanele.yende@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">yaangmathew90@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">yaakoubifarhat121@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">xtremewealth@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">xtremeseby10@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">xspurs2017@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">xleah.juma@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">xhuang025@mymail.sim.edu.sg</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">XAxlx_@hotmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">wyckly@ymail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">www.gbayo2657@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">wshana42@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">workwithnickgomez@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">workwithjonny@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">workwithcoachmike@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="odd">
                                <td class="font-w600">Workwithblaul@gmail.com</td>
                                <td>Premium</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            <tr role="row" class="even">
                                <td class="font-w600">wongzigang19990905@gmail.com</td>
                                <td>Business</td>
                                <td>2017-01-27 00:00:00</td>
                            </tr>

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row font-s13">
            <div class="block">
                <div class="block-header">
                    <ul class="block-options">
                        <li>
                            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </li>
                    </ul>
                    <h2 class="h3 font-w600 text-modern text-center block-title" style="font-size: 24px;"><i class="fa fa-credit-card"></i> My Referral Payments</h2>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped js-dataTable-full-pagination">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Payment Date</th>
                                <th>Commission Earned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key => $invoice)
                            @if ($key%2 == 0)
                            <tr role="row" class="even">
                                @else
                            <tr role="row" class="odd">
                                @endif    
                                <td>{{ $invoice->referred_email }}</td>

                                @if ($invoice->subscription_id == "0137")
                                <td><label class="label label-info">Premium</label></td>
                                @elseif ($invoice->subscription_id == "MX370")
                                <td><label class="label label-danger">Pro</label></td>
                                @elseif ($invoice->subscription_id == "0297")
                                <td><label class="label label-primary">Business</label></td>
                                @elseif ($invoice->subscription_id == "MX970")
                                <td><label class="label label-primary">Mastermind</label></td>
                                @elseif ($invoice->subscription_id == "0167")
                                <td><label class="label label-primary">Business</label></td>
                                @elseif ($invoice->subscription_id == "0197")
                                <td><label class="label label-primary">Business</label></td>
                                @endif

<!--<td>{{ \Carbon\Carbon::parse($invoice->start_date)->toFormattedDateString() }}</td>-->
                                <td>{{ $invoice->charge_created }}</td>
                                @if ($invoice->charge_refunded == 1)
                                <td><label class="label label-default">Refunded</label></td>
                                @else

                                @if ($invoice->subscription_id == "0137")
                                <td><label class="label label-primary">$20</label></td>
                                @elseif ($invoice->subscription_id == "MX370")
                                <td><label class="label label-primary">$200</label></td>
                                @elseif ($invoice->subscription_id == "0297")
                                <td><label class="label label-primary">$50</label></td>
                                @elseif ($invoice->subscription_id == "MX970")
                                <td><label class="label label-primary">$500</label></td>
                                @elseif ($invoice->subscription_id == "0167")
                                <td><label class="label label-primary">$50</label></td>
                                @elseif ($invoice->subscription_id == "0197")
                                <td><label class="label label-primary">$50</label></td>
                                @endif

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
@include('affiliate.js')
@endsection