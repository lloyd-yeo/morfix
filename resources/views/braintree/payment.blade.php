@extends('layouts.app')

@section('css')
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
	      rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="canvas/css/bootstrap.css" type="text/css"/>
	<link rel="stylesheet" href="canvas/style.css" type="text/css"/>
	<link rel="stylesheet" href="canvas/css/dark.css" type="text/css"/>
	<link rel="stylesheet" href="canvas/css/font-icons.css" type="text/css"/>
	<link rel="stylesheet" href="canvas/css/animate.css" type="text/css"/>
	<link rel="stylesheet" href="canvas/css/magnific-popup.css" type="text/css"/>
@endsection

@section('sidebar')
	@include('sidebar', ['page' => 'payment'])
@endsection

@section('content')
	<main id="main-container">

		<div class="content bg-gray-lighter">
			<div class="row items-push">
				<div class="col-sm-7">
					<h1 class="page-heading">
						<i class="si si-bag"></i> Payment
						<small> Upgrade your account now to unlock more training videos & functions.</small>
					</h1>
				</div>
			</div>
		</div>

		<div class="content content-boxed">

			<section id="content">
				<div class="fancy-title title-dotted-border title-center">
					<h3>3 Columns</h3>
				</div>
				<div class="pricing bottommargin clearfix">

					<div class="col-sm-4">

						<div class="pricing-box">
							<div class="pricing-title">
								<h3>Starter</h3>
							</div>
							<div class="pricing-price">
								<span class="price-unit">&euro;</span>7<span class="price-tenure">/mo</span>
							</div>
							<div class="pricing-features">
								<ul>
									<li><strong>Full</strong> Access</li>
									<li><i class="icon-code"></i> Source Files</li>
									<li><strong>100</strong> User Accounts</li>
									<li><strong>1 Year</strong> License</li>
									<li>Phone &amp; Email Support</li>
								</ul>
							</div>
							<div class="pricing-action">
								<a href="#" class="btn btn-danger btn-block btn-lg">Sign Up</a>
							</div>
						</div>

					</div>

					<div class="col-sm-4">

						<div class="pricing-box best-price">
							<div class="pricing-title">
								<h3>Professional</h3>
								<span>Most Popular</span>
							</div>
							<div class="pricing-price">
								<span class="price-unit">&euro;</span>12<span class="price-tenure">/mo</span>
							</div>
							<div class="pricing-features">
								<ul>
									<li><strong>Full</strong> Access</li>
									<li><i class="icon-code"></i> Source Files</li>
									<li><strong>1000</strong> User Accounts</li>
									<li><strong>2 Years</strong> License</li>
									<li><i class="icon-star3"></i>
										<i class="icon-star3"></i>
										<i class="icon-star3"></i>
										<i class="icon-star3"></i>
										<i class="icon-star3"></i></li>
								</ul>
							</div>
							<div class="pricing-action">
								<a href="#" class="btn btn-danger btn-block btn-lg bgcolor border-color">Sign Up</a>
							</div>
						</div>

					</div>

					<div class="col-sm-4">

						<div class="pricing-box">
							<div class="pricing-title">
								<h3>Business</h3>
							</div>
							<div class="pricing-price">
								<span class="price-unit">&euro;</span>19<span class="price-tenure">/mo</span>
							</div>
							<div class="pricing-features">
								<ul>
									<li><strong>Full</strong> Access</li>
									<li><i class="icon-code"></i> Source Files</li>
									<li><strong>500</strong> User Accounts</li>
									<li><strong>3 Years</strong> License</li>
									<li>Phone &amp; Email Support</li>
								</ul>
							</div>
							<div class="pricing-action">
								<a href="#" class="btn btn-danger btn-block btn-lg">Sign Up</a>
							</div>
						</div>

					</div>

				</div>
			</section>

			<form onsubmit="return false;">
				<input type="hidden" name="plan" value="0137test">
				<div id="dropin-container"></div>
				<button class="btn btn-success text-white" id="submit-button" style='
                       background-color:rgb(212, 60, 60);
                       padding: 10px 50px;
                       font-weight: 600;
                       font-size: 15px;
                       border: solid 1px #D3D3D3;
                       text-align: center;
                       text-transform: uppercase;'>Upgrade Now!
				</button>
			</form>

			{{--<div class="row font-s13">--}}
			{{--@if (Auth::user()->vip == 1)--}}
			{{--@include('braintree.table.premium')--}}
			{{--@include('braintree.table.pro')--}}
			{{--@include('braintree.table.business')--}}
			{{--@include('braintree.table.mastermind')--}}
			{{--@elseif (Auth::user()->tier == 1)--}}
			{{--@include('braintree.table.premium')--}}
			{{--@include('braintree.table.pro')--}}
			{{--@elseif (Auth::user()->tier == 2)--}}
			{{--@include('braintree.table.pro', ['unit' => 4])--}}
			{{--@include('braintree.table.business', ['unit' => 4])--}}
			{{--@include('braintree.table.mastermind', ['unit' => 4])--}}
			{{--@elseif (Auth::user()->tier == 3)--}}
			{{--@include('braintree.table.business')--}}
			{{--@include('braintree.table.mastermind')--}}
			{{--@elseif (Auth::user()->tier == 12)--}}
			{{--@include('braintree.table.pro')--}}
			{{--@include('braintree.table.mastermind')--}}
			{{--@elseif (Auth::user()->tier == 22)--}}
			{{--<div class='col-lg-3'></div>--}}
			{{--@include('braintree.table.pro')--}}
			{{--@elseif (Auth::user()->tier == 13)--}}
			{{--<div class='col-lg-3'></div>--}}
			{{--@include('braintree.table.mastermind')--}}
			{{--@endif--}}
			{{--</div>--}}
		</div>
	</main>
@endsection

@section('js')
	<script>
        var button = document.querySelector('#submit-button');

        braintree.dropin.create({
            authorization: '{{ $client_token }}',
            container: '#dropin-container',
            paypal: {
                flow: 'vault',
                buttonStyle: {
                    size: 'small',
                    color: 'gold',
                    shape: 'pill',
                    label: 'buynow',
                    branding: true,
                },
            },
            card: {
                cardholderName: {
                    required: true
                }
            }
        }, function (createErr, instance) {
            button.addEventListener('click', function () {
                instance.requestPaymentMethod(function (err, payload) {
                    // Submit payload.nonce to your server
                    console.log(payload.nonce);
                });
            });
        });
	</script>
@endsection