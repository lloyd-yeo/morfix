<!-- Add Instagram Profile Modal -->
<div class="modal fade" id="modal-verifyprofile" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-popin modal-lg">
		<div class="modal-content">
			<div class="block block-themed block-transparent remove-margin-b">
				<div class="block-header bg-modern">
					<ul class="block-options">
						<li>
							<button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
						</li>
					</ul>
					<h3 class="block-title"><i class='fa fa-instagram'></i> PROFILE VERIFICATION</h3>
				</div>
				<div class="block-content">
					<!-- Form -->
					<!-- Step 2 -->
					<div class="push-30-t push-50">
						<div class="form-group">
							<div class="col-sm-8 col-sm-offset-2">
								<div class="form-material">
									<div class='block'>
										<center><h2 class='push text-modern'><i class='fa fa-info-circle'></i> Verification Steps</h2></center>
										<p class='lead text-center' style="font-size: 18px;">
											Morfix is unable to link-up your profile because of additional verification required from Instagram.
											To solve this, follow the instructions below (3 mins):
										</p>
										<center><h4 class='push'>1. Confirm your login credentials for this profile</h4></center>
										{{--<p>--}}
											{{--Go to <a id="challenge_url" target='_blank' href='http://www.instagram.com'>www.instagram.com</a> & login with the account that you are trying to add to Morfix.--}}
											{{--Leave the page on for now & go back to Morfix.--}}
										{{--</p>--}}

										<div class="form-group">
											<div class="col-sm-12">
												<div class="form-material form-material-primary">
													<input class="form-control" type="text" id="challenge-ig-username" placeholder="Please enter this profile's Instagram Username/Handle">
													<label for="challenge-ig-username">Profile Username/Handle</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<div class="form-material form-material-primary">
													<input class="form-control" type="text" id="challenge-ig-password" placeholder="Please enter this profile's Instagram Password">
													<label for="challenge-ig-password">Profile Password</label>
												</div>
											</div>
										</div>
										<center>
											<button class="btn btn-minw btn-rounded btn-primary" id="challenge-confirm-credentials" style="margin-top: 10px;" type="button">
												Confirm Details
											</button>
										</center>

										<div style="min-width:1px; height: 20px;"></div>

										<div id="challenge-verificationcode-div">
											<center><h3 class='push'>2. Verification Code Required</h3></center>
											<center>
												<h4 class='push' id="challenge-verification-message" style="font-size:12px;">

												</h4>
											</center>
											<div class="form-group">
												<div class="col-sm-12">
													<div class="form-material form-material-primary">
														<input class="form-control" type="text" id="challenge-verification-code" placeholder="Please enter the 6 digit code here.">
														<label for="challenge-verification-code">Verification Code</label>
													</div>
												</div>
											</div>
											<br/>
											<center>
												<button class="btn btn-minw btn-rounded btn-primary" id="challenge-verification-code-submit" style="margin-top: 10px;" type="button">
													Submit
												</button>
											</center>
										</div>

										{{--<center><h4 class='push'>3. Verify "It was me"</h4></center>--}}
										{{--<p>--}}
											{{--Wait for the previous step to fail then switch back to Instagram & refresh the page.--}}
											{{--You will now be presented with something like this:--}}
										{{--</p>--}}

										{{--<center><img src="{{ asset('assets/img/checkpoint/itwasme.jpeg') }}" style="width: 70%;" alt="It was me"></center>--}}
										{{--<p>--}}
											{{--Click "It was me" & then press "Ok".--}}
											{{--After that browse to your profile's page & switch back to Morfix.--}}
										{{--</p>--}}
										{{--<center><h4 class='push'>4. Retry adding</h4></center>--}}
										{{--<div>--}}
											{{--<center><button class="btn btn-primary btn-retry-checkpt" type="button"><i class="fa fa-refresh"></i> Retry</button></center>--}}
										{{--</div>--}}
										{{--<p>--}}
											{{--Depending on whether your account gets added or not.<br/>--}}
											{{--Repeat the process from Step 3.<br/>--}}
											{{--Try for up to a total of 10 times & if you still can't add, do contact live chat on the bottom right hand corner.--}}
										{{--</p>--}}
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END Step 2 -->

					<!-- Steps Navigation -->
					<div class="block-content block-content-mini block-content-full border-t">
						<div class="row">
						</div>
					</div>
					<!-- END Steps Navigation -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END Add Instagram Profile Modal -->