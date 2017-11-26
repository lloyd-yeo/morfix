@include('competition.css')
@extends('layouts.app')
@section('sidebar')
	@include('sidebar', ['page' => 'competition'])
@endsection
@section('content')
	<main id="main-container" style="padding-top: 10px;">
		<div class="bg-video" data-vide-bg="assets/img/videos/hero_tech"
		     data-vide-options="posterType: jpg, position: 50% 75%" style="position: relative;">
			<div style="position: absolute; z-index: -1; top: 0px; left: 0px; bottom: 0px; right: 0px; overflow: hidden; background-size: cover;
         background-color: transparent; background-repeat: no-repeat; background-position: 50% 75%; background-image: none;">
				<img src="assets/img/photos/photo6@2x.jpg" style="margin: auto; position: absolute; z-index: -1; top: 75%; left: 50%; transform: translate(-50%, -75%);
               visibility: visible; opacity: 1; width: 100%; height: auto;"/></div>
			<div>
				<div class="row countdown text-center">
	    		<span class="date">
	    			Duration: {{$month}} {{$startDate}} - {{$endDate}}, {{$year}}
	    		</span>
					<span class="timer"></span>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="row statistics">
				<div class="col-xs-6 col-sm-2 col-lg-2">
					<div class="font-w700 text-gray-darker animated fadeIn">DAILY REFERRAL</div>
					<div class="text-muted animated fadeIn">
						<small><i class="si si-calendar"></i> Date
							<i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip"
							   data-placement="top" title="Commissions that will be paid out the 25th of this month"
							   type="button"></i>
						</small>
					</div>
					<span class="h2 text-moneygreen animated flipInX">{{ $dailyReferral }}</span>
				</div>
				<div class="col-xs-6 col-sm-2 col-lg-2">
					<div class="font-w700 text-gray-darker animated fadeIn">TOTAL REFERRAL</div>
					<div class="text-muted animated fadeIn">
						<small><i class="si si-calendar"></i> Start Date - End Date
							<i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip"
							   data-placement="top" title="Commissions that will be paid out the 25th of this month"
							   type="button"></i>
						</small>
					</div>
					<span class="h2 text-moneygreen animated flipInX">{{ $totalReferral }}</span>
				</div>
				<div class="col-xs-6 col-sm-6 col-lg-4">
					<div class="font-w700 text-gray-darker animated fadeIn">MY COMPETITION RANKING</div>
					<div class="text-muted animated fadeIn">
						<small><i class="si si-trophy"></i> All Time</small>
					</div>
					@if ($ranking == 1)
						<span class="h2 font-w300 text-modern animated flipInX">FIRST PLACE!</span>
					@elseif ($ranking == 2)
						<span class="h2 font-w300 text-modern animated flipInX">SECOND PLACE!</span>
					@elseif ($ranking == 3)
						<span class="h2 font-w300 text-modern animated flipInX">THIRD PLACE!</span>
					@else
						<span class="h2 font-w300 text-modern animated flipInX">#{{ $ranking }}</span>
					@endif
				</div>
			</div>
			<div class="row">
				@include('competition.ranking')
				<div class="col-lg-6 announcements">
					<!-- News -->
					<div class="block">
						<div class="block-header">
							<ul class="block-options">
								<li>
									<button type="button" data-toggle="block-option" data-action="refresh_toggle"
									        data-action-mode="demo"><i class="si si-refresh"></i></button>
								</li>
							</ul>
							<h3 class="block-title"> Announcements</h3>
						</div>
						<div class="block-content">
							<ul class="list list-timeline pull-t">
							</ul>
						</div>
					</div>
					<!-- END News -->
				</div>

			</div>
			<div class="row">
				<div class="referral">
					<div class="block">
						<div id="follower-chart-header-{{ $igProfiles->id }}" class="block-header">
							<h3 class="block-title"><i class="fa fa-instagram"></i> YOUR REFERRAL CHART</h3>
						</div>

						<div class="block-content block-content-full bg-gray-lighter text-center chart-container">
							<!--Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/-->
							<div id="follower-count-chart-{{ $igProfiles->id }}" style="height: 374px;">
								<canvas class="follower-count-chart-lines" data-csv="{{ $analysis }}"
								        data-label="{{ $analysisLabel }}"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
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
						@foreach ($allReferrals as $key => $referral)
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
										@elseif ($referral->tier == 11)
											<td>Business</td>
										@elseif ($referral->tier == 12)
											<td>Business</td>
										@elseif ($referral->tier == 22)
											<td>Mastermind</td>
										@elseif ($referral->tier == 23)
											<td>Mastermind</td>
										@endif
										<td>{{ $referral->created_at }}</td>
										@if ($referral->paypal === 1)
											<td><img style="height: 20px;" src="{{ asset('assets/img/logo/paypal-logo.png') }}" /></td>
										@else
											<td><img style="height: 20px;" src="{{ asset('assets/img/logo/credit-card.png') }}" /></td>
										@endif
									</tr>
								@endif
								@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="row">
				<div class="block-header">
					<h3 class="block-title"><i class="fa fa-address-card"></i> LIST OF REFERRALS</h3>
				</div>
				<table class="table table-striped">
					<thead class="bg-primary">
					<td>Email Address</td>
					<td>Tier</td>
					</thead>
					<tbody>
					@if(sizeof($allReferrals) > 0)
						@foreach($allReferrals as $referral)
							<tr>
								<td>{{ $referral->email }}</td>
								@if ($referral->tier == 2)
									<td>Premium</td>
								@elseif ($referral->tier == 3)
									<td>Pro</td>
								@elseif ($referral->tier == 12)
									<td>Premium & Business</td>
								@elseif ($referral->tier == 13)
									<td>Pro & Business</td>
								@elseif ($referral->tier == 23)
									<td>Pro & Mastermind</td>
								@else
								@endif
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="resource">
					<div class="block-header">
						<h3 class="block-title"><i class="fa fa-bars"></i> RESOURCES</h3>
					</div>
				</div>
			</div>
			<div class="row footer">
				<span class="pull-right">Terms and Conditions</span>
			</div>
		</div>
	</main>
@endsection

@section('js')
	@include('dashboard.js')
	@include('competition.js')
@endsection

