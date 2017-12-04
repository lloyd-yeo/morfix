@extends('layouts.app')

@section('css')
	@include('dashboard.css')
@endsection

@section('sidebar')
	@include('sidebar', ['page' => 'home'])
@endsection

@section('content')
	<main id="main-container" style="padding-top: 10px;">
	@include('dashboard.hero')
	@include('dashboard.statistics')
	<!-- Page Content -->
		<div class="content">
			<div class="row">
				<div class="col-lg-8">
					<!-- News -->
					<div class="block">
						<div class="block-header">
							<ul class="block-options">
								<li>
									<button type="button" data-toggle="block-option" data-action="refresh_toggle"
									        data-action-mode="demo"><i class="si si-refresh"></i></button>
								</li>
							</ul>
							<h3 class="block-title"> Updates</h3>
						</div>
						<div class="block-content">
							<ul class="list list-timeline pull-t">
								@foreach ($user_updates as $update)
									@include('dashboard.update', ['update' => $update])
								@endforeach
							</ul>
						</div>
					</div>
					<!-- END News -->
				</div>


				@include('dashboard.leaderboard')

			</div>
			<div class="row">
				@foreach ($user_ig_profiles as $ig_profile)
					<div class="col-lg-4 insta-profile" data-id="{{ $ig_profile->id }}">
						<div class="block block-rounded">
							<div class="block-header">
								<ul class="block-options">
									<li>
										<button type="button remove-profile-btn" data-id="{{ $ig_profile->id }}"
										        data-user-id="{{ Auth::user()->id }}" class="remove-profile-btn">
											<i class="si si-close"></i>
										</button>
									</li>
								</ul>
								<div class="block-title">{{ $ig_profile->profile_full_name }}</div>
							</div>
							<div class="block-content block-content-full bg-primary text-center bg-image"
							     style="background-image: url('assets/img/photos/photo2.jpg');">
								<img class="img-avatar img-avatar-thumb" src="{{ $ig_profile->profile_pic_url }}"
								     alt="">
								<div class="font-s13 push-10-t"><i
											class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}</div>
							</div>
							<div class="block-content">
								<div class="block-content">
									<div class="row items-push text-center font-w700">
										<div class="col-xs-6">
											<div class="push-5"><i class="si si-camera fa-2x"></i></div>
											<div class="h5 font-w300">{{ $ig_profile->num_posts }} Posts</div>
										</div>
										<div class="col-xs-6">
											<div class="push-5"><i class="si si-users fa-2x"></i></div>
											<div class="h5 font-w300">{{ $ig_profile->follower_count }} Followers</div>
										</div>
									</div>
								</div>

								@if ($ig_profile->feedback_required == 1 || $ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 )
									<div class="text-center text-danger push">
										<span>We've encountered issues with your account. Mouse-over the icons below to find out more:</span>
									</div>
								@endif

								<div class="text-center push">

									@if ($ig_profile->checkpoint_required == 1)
										<a class="text-danger checkpoint-btn" href="javascript:void(0)"
										   data-profile-id="{{ $ig_profile->id }}" data-toggle="popover"
										   data-original-title="Verification Required"
										   data-content="Morfix needs you to click on 'It was me' when prompted by Instagram to restore connectivity. Click to resolve.">
											<i class="fa fa-2x fa-fw fa-unlink"></i>
										</a>
									@else
									@endif

									@if ($ig_profile->incorrect_pw == 1)
										<a class="text-danger incorrect-pw-btn" href="javascript:void(0)"
										   data-profile-id="{{ $ig_profile->id }}" data-toggle="popover"
										   data-original-title="Incorrect Password"
										   data-content="The password on your account currently is different from the one you supplied us. Click to resolve.">
											<i class="fa fa-2x fa-fw fa-asterisk"></i>
											<strong> Mouse over the cross to find out more.</strong>
										</a>
									@else
									@endif

									@if ($ig_profile->invalid_user == 1)
										<a class="text-danger" href="javascript:void(0)"
										   data-profile-id="{{ $ig_profile->id }}" data-toggle="popover"
										   data-original-title="Invalid Username"
										   data-content="The username on this profile is invalid. To resolve, please remove this profile & add a valid one.">
											<i class="fa fa-2x fa-fw fa-user"></i>
										</a>
									@else
									@endif

									@if ($ig_profile->account_disabled == 1)
										<a class="text-danger" href="javascript:void(0)"
										   data-profile-id="{{ $ig_profile->id }}" data-toggle="popover"
										   data-original-title="Account Disabled"
										   data-content="This account has been disabled by Instagram. Do contact Instagram to see if they can enable your account again.">
											<i class="fa fa-2x fa-fw fa-user-times"></i>
										</a>
									@endif

									@if ($ig_profile->feedback_required == 1)
										<a class="text-danger" href="javascript:void(0)"
										   data-profile-id="{{ $ig_profile->id }}" data-toggle="popover"
										   data-original-title="Account Disabled"
										   data-content="This account has been soft-banned by Instagram. Do check back in awhile.">
											<i class="fa fa-2x fa-fw fa-user-times"></i>
										</a>
									@endif

								</div>
								<table class="table table-borderless table-striped font-s13">
									<tbody>
									<tr>
										<td class="font-w600" style="width: 30%;"><i
													class="fa fa-fw fa-envelope text-primary-light"></i> DM Status
										</td>
										@if ($ig_profile->auto_dm_new_follower == 1)
											@if ($ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 || $ig_profile->feedback_required == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"></i>
													Suspended due to profile errors
												</td>
											@else
												{{-- If error isnt there, proceed as usual --}}
												@if ($ig_profile->dm_probation === 0 && $ig_profile->temporary_ban === NULL)
													<td class='text-success'><i class="fa fa-fw fa-check-square"></i>
														Healthy
													</td>
												@elseif ($ig_profile->temporary_ban === NULL && $ig_profile->dm_probation === 1)
													<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"
													                            type="button" data-toggle="popover"
													                            data-original-title="DM Probation"
													                            data-content="The suspension on sending DMs has been lifted on this account. However to simulate human behaviour we have imposed probation to reduce the chances of timing out."></i>
														Probation
														<small>(mouse-over the cross-icon to find out more.)</small>
													</td>
												@elseif ($ig_profile->temporary_ban != NULL)
													<td class='text-danger'><i class="fa fa-fw fa-times-circle"
													                           type="button" data-toggle="popover"
													                           data-original-title="DM Timeout"
													                           data-content="Instagram has placed a temporary suspension for sending out DMs using this account. This is routine & there's nothing to worry about, Morfix will resume sending out DMs the earliest time possible."></i>
														<small>Timeout (mouse-over the cross-icon to find out more.)
														</small>
													</td>
												@endif
											@endif
										@else
											<td class='text-modern'><i class="fa fa-fw fa-toggle-off"></i> Turned Off
											</td>
										@endif
									</tr>
									<tr>
										<td class="font-w600"><i class='fa fa-heart text-danger'></i> Likes</td>
										@if ($ig_profile->auto_like == 1)

											@if ($ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 || $ig_profile->feedback_required == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"></i>
													Suspended due to profile errors
												</td>
											@elseif ($ig_profile->auto_like_ban == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-info-circle"></i>
													<small>Throttled by Instagram Temporarily - Next Attempt
													       at {{ Carbon\Carbon::parse($ig_profile->auto_like_ban_time)->toDayDateTimeString() }}
													       (GMT +8)
													</small>
												</td>
											@else
												{{-- If error isnt there, proceed as usual --}}
												<td class='text-success'><i class="fa fa-fw fa-check-square"></i>
													Healthy
												</td>
											@endif

										@else
											<td class='text-modern'><i class="fa fa-fw fa-toggle-off"></i> Turned Off
											</td>
										@endif
									</tr>

									<tr>
										<td class="font-w600"><i class='fa fa-comments text-primary'></i> Comment</td>
										@if ($ig_profile->auto_comment == 1)

											@if ($ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 || $ig_profile->feedback_required == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"></i>
													Suspended due to profile errors
												</td>
											@else
												{{-- If error isnt there, proceed as usual --}}
												<td class='text-success'><i class="fa fa-fw fa-check-square"></i>
													Healthy
												</td>
											@endif

										@else
											<td class='text-modern'><i class="fa fa-fw fa-toggle-off"></i> Turned Off
											</td>
										@endif
									</tr>

									<tr>
										<td class="font-w600"><i class='si si-user-follow text-modern'></i> Follow</td>
										@if ($ig_profile->auto_follow == 1)
											@if ($ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 || $ig_profile->feedback_required == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"></i>
													Suspended due to profile errors
												</td>
											@elseif ($ig_profile->auto_follow_ban == 1)
												<td class='text-warning'><i class="fa fa-fw fa-info-circle"></i>
													<small>Throttled by Instagram Temporarily - Next Attempt
													       at {{ Carbon\Carbon::parse($ig_profile->next_follow_time)->toDayDateTimeString() }}
													       (GMT +8)
													</small>
												</td>
											@else
												{{-- If error isnt there, proceed as usual --}}
												<td class='text-success'><i class="fa fa-fw fa-check-square"></i>
													Healthy
												</td>
											@endif
										@else
											<td class='text-modern'><i class="fa fa-fw fa-toggle-off"></i> Turned Off
											</td>
										@endif
									</tr>

									<tr>
										<td class="font-w600"><i class='si si-user-follow text-lightred'></i> Unfollow
										</td>
										@if ($ig_profile->auto_unfollow == 1)
											@if ($ig_profile->checkpoint_required == 1 ||$ig_profile->incorrect_pw == 1 ||$ig_profile->invalid_user == 1 ||$ig_profile->account_disabled == 1 || $ig_profile->feedback_required == 1)
												{{-- If error is there, show suspension --}}
												<td class='text-warning'><i class="fa fa-fw fa-ellipsis-h"></i>
													Suspended due to profile errors
												</td>
											@else
												{{-- If error isnt there, proceed as usual --}}
												<td class='text-success'><i class="fa fa-fw fa-check-square"></i>
													Healthy
												</td>
											@endif
										@else
											<td class='text-modern'><i class="fa fa-fw fa-toggle-off"></i> Turned Off
											</td>
										@endif
									</tr>


									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-lg-8 follower-chart">
						<div class="block">
							<div id="follower-chart-header-{{ $ig_profile->id }}" class="block-header">
								<h3 class="block-title"><i
											class="fa fa-instagram"></i> {{ $ig_profile->insta_username }}'s DAILY
								                                                                          FOLLOWER
								                                                                          TRENDS</h3>
							</div>

							<div class="block-content block-content-full bg-gray-lighter text-center chart-container">
								<!--Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/-->
								<div id="follower-count-chart-{{ $ig_profile->id }}" style="height: 374px;">
									<canvas class="follower-count-chart-lines"
									        data-csv="{{ $user_ig_analysis[$ig_profile->insta_username] }}"
									        data-label="{{ $user_ig_analysis_label[$ig_profile->insta_username] }}"></canvas>
								</div>
							</div>
						</div>
					</div>

				@endforeach
			</div>

			@include('dashboard.modal.addprofile')

			<script type="text/javascript">
                $(".insta-profile").each(function (index) {
                    var $instaId = $(this).attr("data-id");
                    $("#follower-count-chart-" + $instaId).css("height", ($(this).height() - 24 - 40 - $("#follower-chart-header-" + $instaId).height() - 15 - 15) + "px");
                });
			</script>
		</div>
		<!-- END Page Content -->

		@if (Auth::user()->close_dashboard_tut == 0)
			@include('dashboard.modal.tutorial')
		@endif

		@include('dashboard.modal.checkpoint')
	</main>
@endsection

@section('js')
	@include('dashboard.js')
@endsection
