@extends('layouts.app')

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

			<form onsubmit="return false;">
				<input type="hidden" name="plan" value="0137test">
				<div id="dropin-container"></div>
				<button class="btn btn-success" id="submit-button">Pay!</button>
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
                flow: 'checkout',
                amount: 37.00,
                currency: 'USD'
            },
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