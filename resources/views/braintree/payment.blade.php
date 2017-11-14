@extends('layouts.app')

@section('css')
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
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

			<div class="fancy-title title-dotted-border title-center">
				<h3 style="background-color: #f5f5f5;">
					<i class="fa fa-instagram"></i> Elevate Your Instagram Pressence Now!</h3>
			</div>
			<div class="pricing bottommargin clearfix">
				@if (Auth::user()->tier == 1)
					<div class="col-sm-4">
						<div class="pricing-box">
							<div class="pricing-title">
								<h3>Premium</h3>
							</div>
							<div class="pricing-price" style="color: rgb(212, 60, 60);">
								<span class="price-unit">&dollar;</span>37<span class="price-tenure">/mo</span>
							</div>
							<div class="pricing-features">
								<ul>
									<li><strong>No</strong> Morfix.co branding</li>
									<li>Full Speed</li>
									<li><strong>High</strong> Priority Support</li>
									<li>Instagram Affiliate Training</li>
									<li>Auto Interaction (Like, Comment Follow, Unfollow)</li>
									<li>Greet New Followers With Direct Message</li>
									<li>Unlimited Scheduled Posts</li>
									<li>First Comment Function</li>
									<li><strong>Private</strong> Facebook Group</li>
								</ul>
							</div>
							<div class="pricing-action">
								<button class="btn btn-success upgrade-btn" data-plan="0137" style='
                       background-color:rgb(212, 60, 60);
                       padding: 10px 50px;
                       font-weight: 600;
                       font-size: 15px;
                       border: solid 1px #D3D3D3;
                       text-align: center;
                       text-transform: uppercase;'>Upgrade Now!
								</button>
							</div>
						</div>

					</div>

					<div class="col-sm-4">
						<div class="pricing-box best-price">
							<div class="pricing-title">
								<h3>Pro</h3>
								<span>Most Savings</span>
							</div>
							<div class="pricing-price" style="color: rgb(212, 60, 60);">
								<span class="price-unit">&dollar;</span>370<span class="price-tenure">/yr</span>
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
								<button class="btn btn-success text-white upgrade-btn" data-toggle="modal"
								        data-target=".upgrade-modal" data-plan="0137test" style='
                       background-color:rgb(212, 60, 60);
                       padding: 10px 50px;
                       font-weight: 600;
                       font-size: 15px;
                       border: solid 1px #D3D3D3;
                       text-align: center;
                       text-transform: uppercase;'>Upgrade Now!
								</button>
							</div>
						</div>
					</div>
				@elseif (Auth::user()->tier == 2)
					<div class="col-sm-4">

						<div class="pricing-box">
							<div class="pricing-title">
								<h3>Business</h3>
							</div>
							<div class="pricing-price" style="color: #3C71AF;">
								<span class="price-unit">&dollar;</span>97<span class="price-tenure">/mo</span>
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
								<button class="btn btn-success text-white upgrade-btn" data-plan="0297" style='
				                       background-color:#3C71AF;
				                       padding: 10px 50px;
				                       font-weight: 600;
				                       font-size: 15px;
				                       border: solid 1px #D3D3D3;
				                       text-align: center;
				                       text-transform: uppercase;'>Upgrade Now!
								</button>
							</div>
						</div>
					</div>
				@endif

			</div>

			<div class="modal fade upgrade-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
			     aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-body">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
								</button>
								<h4 class="modal-title" id="myModalLabel">Payment</h4>
							</div>
							<div class="modal-body">
								<form onsubmit="return false;">
									<input type="hidden" name="plan" value="0137test">
									<div id="dropin-container"></div>
									<button class="btn btn-success text-white" id="submit-button" style='
						                    background-color:#3C71AF;
						                    padding: 10px 50px;
						                    font-weight: 600;
						                    font-size: 15px;
						                    border: solid 1px #D3D3D3;
						                    text-align: center;
						                    text-transform: uppercase;'>Upgrade Now!
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

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
		var $plan = "0137";
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
                    // console.log(payload.nonce);

                });
            });
        });

        $(".upgrade-btn").on("click", function(){
            $plan = $(this).data('plan');
            console.log($plan);
        });

	</script>
@endsection