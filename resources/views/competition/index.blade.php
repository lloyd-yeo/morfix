@extends('layouts.app')
@section('css')
	@include('competition.css')
@endsection
@section('sidebar')
	@include('sidebar', ['page' => 'competition'])
@endsection

@section('content')
	<main id="main-container" style="padding-top: 10px;">
		<div class="bg-video" data-vide-bg="assets/img/videos/hero_tech"
		     data-vide-options="posterType: jpg, position: 50% 75%" style="position: relative;">
			<div style="position: absolute; z-index: -1; top: 0px; left: 0px; bottom: 0px; right: 0px; overflow: hidden; background-size: cover;
         background-color: transparent; background-repeat: no-repeat; background-position: 50% 75%; background-image: none;">
				<img class="" src="assets/img/competition/hero.jpg" style="filter: brightness(50%); margin: auto; position: absolute; z-index: -1; top: 75%; left: 50%; transform: translate(-50%, -75%);
               visibility: visible; opacity: 1; width: 100%; height: auto;"/></div>
			<div>
				<div class="row countdown text-center">
	    		<span class="date font-w700" style="font-size: 30px;">
				    <span>MORFIX FIRST EVER AFFILIATE COMPETITION!</span><br/>
				    <span style="font-size: 25px;">COMPETITION PERIOD: </span><br/>
				    <span style="font-size: 25px;">{{ $month }} {{ $startDate }} - {{ $endDate }}, {{ $year }}</span>
	    		</span>
					<span class="timer font-w700" style="margin-top: 15px; font-size: 18px;"></span>
					<br/>
					<span class="font-w500" style="width: 100%; float: left; margin-top: 15px; font-size: 18px;">
						<span id="hourTimer"></span> hours,
						<span id="minuteTimer"></span> minutes,
						<span id="secondTimer"></span> seconds remaining
					</span>
				</div>
			</div>
		</div>

		<!-- Stats -->
		<div class="content bg-white border-b">
			<div class="row items-push text-uppercase">

				<div class="col-xs-6 col-sm-2 col-lg-4">
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
				<div class="col-xs-6 col-sm-2 col-lg-4">
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
		</div>
		<!-- END Stats -->


		<div class="content content-boxed">
			<div class="row statistics" style="display:none;">
				<div class="col-xs-6 col-sm-2 col-lg-3">
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
				<div class="col-xs-6 col-sm-2 col-lg-3">
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
				<div class="col-xs-6 col-sm-6 col-lg-3">
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

				<div class="col-xs-6 col-sm-6 col-lg-3">
					<div class="font-w700 text-gray-darker animated fadeIn">COMPETITION TIME NOW (CST)</div>
					<div class="text-muted animated fadeIn">
						<small><i class="si si-clock"></i> Time Now</small>
					</div>
					<span class="h2 font-w300 text-modern animated flipInX">{{ $competition_time }}</span>
				</div>
			</div>


			<div class="row">
				<div class="col-lg-6">
					<div class="col-xs-6 col-sm-4 col-lg-12">
						<a class="block block-link-hover2 text-center"
						   href="#!"
						   data-toggle="modal"
						   data-target="#modal-terms-conditions">
							<div class="block-content block-content-full bg-danger">
								<i class="si si-docs fa-4x text-white"></i>
								<div class="font-w600 text-white-op push-15-t">[READ FIRST!] Terms & Conditions (Click here!)</div>
							</div>
						</a>
					</div>

				</div>
				<div class="col-lg-6">
					<div class="col-xs-6 col-sm-4 col-lg-12">
						<a class="block block-link-hover2 text-center"
						   href="#!"
						   data-toggle="modal" data-target="#modal-prizes">
							<div class="block-content block-content-full bg-modern">
								<i class="si si-diamond fa-4x text-white"></i>
								<div class="font-w700 text-white-op push-15-t">ATTRACTIVE PRIZES (Click here!)</div>
							</div>
						</a>
					</div>
				</div>
			</div>


			<div class="row">
				@include('competition.ranking')
				<div class="col-lg-4 announcements">
					<!-- News -->
					<div class="block">
						<div class="block-header">
							<h3 class="block-title"> Medal Legends</h3>
						</div>
						<div class="block-content">
							<table class="table table-borderless remove-margin-b remove-margin-t font-s13">
								<tbody>
									<tr>
										<td class="font-w700">
											<span class='text-primary'>20 REFERRALS</span>
										</td>
										<td>
											<img class="pull-right" style="width: 32px;"
											     src="{{  asset('assets/img/competition/medal20diamond.png') }}">
										</td>
									</tr>

									<tr>
										<td class="font-w700">
											<span class='text-primary'>10 REFERRALS</span>
										</td>
										<td>
											<img class="pull-right" style="width: 32px;"
											     src="{{  asset('assets/img/competition/medal5.png') }}">
										</td>
									</tr>

									<tr>
										<td class="font-w700">
											<span class='text-primary'>5 REFERRALS</span>
										</td>
										<td>
											<img class="pull-right" style="width: 32px;"
											     src="{{  asset('assets/img/competition/moneymedal.png') }}">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END News -->

					<!-- News -->
					<div class="block">
						<div class="block-header">
							<h3 class="block-title"> Sales Legends</h3>
						</div>
						<div class="block-content">
							<table class="table table-borderless remove-margin-b remove-margin-t font-s13">
								<tbody>
								<tr>
									<td class="font-w700">
										<span class='text-primary'>$500 USD</span>
									</td>
									<td>
										<img class="pull-right" style="width: 32px;"
										     src="{{  asset('assets/img/competition/500sales.png') }}">
									</td>
								</tr>

								<tr>
									<td class="font-w700">
										<span class='text-primary'>$200 USD</span>
									</td>
									<td>
										<img class="pull-right" style="width: 32px;"
										     src="{{  asset('assets/img/competition/200sales.png') }}">
									</td>
								</tr>

								<tr>
									<td class="font-w700">
										<span class='text-primary'>$100 USD</span>
									</td>
									<td>
										<img class="pull-right" style="width: 32px;"
										     src="{{  asset('assets/img/competition/100sales.png') }}">
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END News -->
				</div>
			</div>

			<div class="row">
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
							@foreach ($competition_updates as $update)
								@include('competition.update', ['update' => $update])
							@endforeach
						</ul>
					</div>
				</div>
				<!-- END News -->
			</div>

			<div class="row">
				<div class="referral col-lg-12">
					<div class="block">
						<div id="follower-chart-header" class="block-header">
							<h3 class="block-title"><i class="fa fa-instagram"></i> YOUR REFERRAL CHART</h3>
						</div>

						<div class="block-content block-content-full bg-gray-lighter text-center chart-container">
							<!--Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/-->
							<div id="follower-count-chart" style="height: 374px;">
								<canvas class="follower-count-chart-lines" data-csv="{{ $analysis }}"
								        data-label="{{ $analysisLabel }}"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="block">
					<div class="block-header">
						<h3 class="block-title"><i class="fa fa-users"></i> LIST OF REFERRALS</h3>
					</div>
					<div class="block-content block-content-full">
						<table class="table table-bordered table-striped js-dataTable-full-pagination-competition">
							<thead class="bg-primary">
							<tr>
								<th>Email</th>
								<th class="hidden-xs">User Tier</th>
								<th>Join Date</th>
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
											@elseif ($referral->tier == 13)
												<td>Business & Pro</td>
											@elseif ($referral->tier == 11)
												<td>Business</td>
											@elseif ($referral->tier == 12)
												<td>Business & Premium</td>
											@elseif ($referral->tier == 22)
												<td>Mastermind & Premium</td>
											@elseif ($referral->tier == 23)
												<td>Mastermind & Pro</td>
											@endif
											<td>{{ $referral->created_at }}</td>
										</tr>
									@endif
									@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="resource">
					<div class="block-header">
						<h3 class="block-title"><i class="fa fa-bars"></i> RESOURCES</h3>
					</div>
					<div class="block-content block-content-full">

						<div class="row">
							<div class="col-lg-3">
								<a href="https://clickfunnels.com/?cf_affiliate_id=768373&amp;affiliate_id=768373">
									<img src="{{ asset('assets/img/banner/banner1.jpeg') }}" width="300" height="250" class="affIMGURL">
								</a>
							</div>
							<div class="col-lg-3">
								<a href="https://clickfunnels.com/?cf_affiliate_id=768373&amp;affiliate_id=768373">
									<img src="{{ asset('assets/img/banner/banner2.jpeg') }}" width="300" height="250" class="affIMGURL">
								</a>
							</div>
							<div class="col-lg-3">
								<a href="https://clickfunnels.com/?cf_affiliate_id=768373&amp;affiliate_id=768373">
									<img src="{{ asset('assets/img/banner/banner3.jpeg') }}" width="300" height="250" class="affIMGURL">
								</a>
							</div>
							<div class="col-lg-3">
								<a href="https://clickfunnels.com/?cf_affiliate_id=768373&amp;affiliate_id=768373">
									<img src="{{ asset('assets/img/banner/banner4.jpeg') }}" width="300" height="250" class="affIMGURL">
								</a>
							</div>
						</div>

						<div class="row" style="margin-top: 20px;">
							<div class="block block-theme">
								<div class="block-content">
									<div class="content content-narrow">
										<div class='row'>
											<h1 class="font-w700 push">Email #1 Swipe</h1>

											<div class="nice-copy">
												<span class="font-w700">Email #1</span>
												<p>
													Sample Subject Lines:
													<br/><br/>
													(URGENT) want a free Morfix account?
													<br/><br/>
													watch me gain IG Followers in 10 minutes!
													<br/><br/>
													Morfix Demo (video inside)<br/><br/>

													(Login Info) your Morfix account<br/><br/>

													Morfix is LIVE<br/>
													Morfix - get a FREE account<br/><br/>

													Sample Body Copy:<br/><br/>

													There's a new software product that's taking over the internet.  It's called Morfix, and I just hooked you up with a FREE account!<br/><br/>

													<< Affiliate Link >><br/><br/>

													This new software will help your Instagram account:<br/><br/>

													  - Grow Thousands Of Followers A Month on Automation..<br/>

													  - Automated Direct Messages...<br/>

													  - Schedule Posts...<br/>

													  - Trainings on Instagram Income…<br/>

													  - Going Viral...<br/>

													  - And a WHOLE LOT MORE!<br/><br/>

													Want to see a demo of the software in action? Check it out here:<br/><br/>

													<< Affiliate Link >><br/><br/>

													Thanks,<br/>
													<< Your Name>><br/><br/>

													P.S. - After you see the demo video, you can get a 7 day free account, and go setup with your IG Account. You'll be blown away with what's possible:<br/><br/>

													<< Affiliate Link >>

												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: 20px;">
							<div class="block block-themed">]
								<div class="block-content">
									<div class="content content-narrow">
										<div class='row'>
											<h1 class="font-w700 push">Email #2 Swipe</h1>
											<div class="nice-copy">
												<span class="font-w700">Email #2</span>
												<p>
													Sample Subject Lines:<br/><br/>

													Smart Followers Growth... Morfix is AMAZING!<br/><br/>

													9 "Growth" Niche templates... free!<br/><br/>

													What's the catch?  This is TOO good…<br/><br/>

													I just hooked you up - FREE Morfix account!<br/><br/>

													Did you see what Mike said?<br/><br/>

													Sample Body Copy:<br/><br/>

													Over the past 12 months, a new tool called Morfix has taken over the internet.  <br/><br/>

													Over 3,000 Instagramers and Influencers have setup their Instagram account with it, and I was able to get you a free 7 day trial!<br/><br/>

													<< Affiliate Link >><br/><br/>

													Listen to what Mike Benson said after getting his free trial account:<br/><br/>

													“...I never knew growing my followers can be so easy. Morfix has done all the heavy lifting for me. All I need to do is focus now is on giving my audience values through my Instagram and Morfix even has a training on that…” <br/><br/>

													<< Affiliate Link >><br/><br/>

													This account will give you access for ONE full week so you can look under the hood and have some fun with Morfix!<br/><br/>

													Thanks,<br/>
													<< Your Name>><br/><br/>

													P.S. - After you see the demo video, you can get a free account, and go setup your IG account. You'll be blown away with what's possible:<br/><br/>

													<< Affiliate Link >>


												</p>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-6 col-sm-4 col-lg-4">
								<a class="block block-link-hover2 text-center"
								   href="#!"
								   data-toggle="modal"
								   data-target="#modal-swipe1">
									<div class="block-content block-content-full bg-primary">
										<i class="si si-docs fa-4x text-white"></i>
										<div class="font-w600 text-white-op push-15-t">Email Swipes #1</div>
									</div>
								</a>
							</div>
							<div class="col-xs-6 col-sm-4 col-lg-4">
								<a class="block block-link-hover2 text-center"
								   href="#!"
								   data-toggle="modal"
								   data-target="#modal-swipe2">
									<div class="block-content block-content-full bg-primary">
										<i class="si si-docs fa-4x text-white"></i>
										<div class="font-w600 text-white-op push-15-t">Email Swipes #2</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			@include('competition.modal.termsandconditions')
			@include('competition.modal.prizes')

			@include('competition.modal.swipes.email1')
			@include('competition.modal.swipes.email2')
		</div>
	</main>
@endsection

@section('js')
	@include('dashboard.js')
	@include('competition.js')
@endsection

