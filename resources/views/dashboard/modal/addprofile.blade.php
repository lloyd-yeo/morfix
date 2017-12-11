<!-- Add Instagram Profile Modal -->
<div class="modal fade" id="modal-addprofile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin modal-lg">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title"><i class='fa fa-instagram'></i> ADD PROFILE</h3>
                </div>
                <div class="block-content">
                    <!-- Validation Wizard (.js-wizard-validation class is initialized in js/pages/base_forms_wizard.js) -->
                    <!-- For more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/ -->
                    <div class="js-wizard-validation block">
                        <!-- Step Tabs -->
                        <ul class="nav nav-tabs nav-tabs-alt nav-justified">
                            <li class="active">
                                <a class="inactive" href="#validation-step1" data-toggle="tab">1. Link Profile</a>
                            </li>
                            <li>
                                <a class="inactive" href="#validation-step2" data-toggle="tab">2. Verification</a>
                            </li>
                            <li>
                                <a class="inactive" href="#validation-step3" data-toggle="tab">3. Finish</a>
                            </li>
                        </ul>
                        <!-- END Step Tabs -->

                        <!-- Form -->
                        <!-- jQuery Validation (.js-form2 class is initialized in js/pages/base_forms_wizard.js) -->
                        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                        <form class="js-form2 form-horizontal" action="base_forms_wizard.html" method="post">
                            <!-- Steps Content -->
                            <div class="block-content tab-content">
                                <!-- Step 1 -->
                                <div class="tab-pane fade fade-right in push-30-t push-50 active" id="validation-step1">
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-2">
                                            <div class="form-material form-material-primary">
                                                <input class="form-control" type="text" id="validation-ig-username" name="validation-ig-username" placeholder="Please enter your Instagram Username/Handle">
                                                <label for="validation-ig-username">Profile Username/Handle</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-2">
                                            <div class="form-material form-material-primary">
                                                <input class="form-control" type="text" id="validation-ig-password" name="validation-ig-password" placeholder="Please enter your Instagram Password">
                                                <label for="validation-ig-password">Profile Password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Step 1 -->

                                <!-- Step 2 -->
                                <div class="tab-pane fade fade-right push-30-t push-50" id="validation-step2">
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-2">
                                            <div class="form-material">
                                                <div class='block'>
                                                    <center><h1 class='push text-modern'><i class='fa fa-info-circle'></i> Verification Required</h1></center>
                                                    <p class='lead'>
                                                        Morfix is unable to link-up your profile because of additional verification required from Instagram.
                                                        To solve this, follow the instructions below (3 mins):
                                                    </p>
                                                    <center><h4 class='push'>1. Login to Instagram with your account</h4></center>
                                                    <p>
                                                        Go to <a id="challenge_url" target='_blank' href='http://www.instagram.com'>www.instagram.com</a> & login with the account that you are trying to add to Morfix.
                                                        Leave the page on for now & go back to Morfix.
                                                    </p>
                                                    <center><h4 class='push'>2. Retry adding</h4></center>
                                                    <div>
                                                        <center><button class="btn btn-primary btn-retry" type="button"><i id="retry-btn-spinner" class="fa fa-refresh"></i> Retry</button></center>
                                                    </div>
                                                    <br/>
                                                    <p class='text-danger text-center'>
                                                        <b>It will fail again! Do not worry.</b>
                                                    </p>
                                                    <center><h4 class='push'>3. Verify "It was me"</h4></center>
                                                    <p>
                                                        Wait for the previous step to fail then switch back to Instagram & refresh the page.
                                                        You will now be presented with something like this:
                                                    </p>
                                                    <center><img src="{{ asset('assets/img/checkpoint/itwasme.jpeg') }}" style="width: 70%;" alt="It was me"></center>
                                                    <p>
                                                        Click "It was me" & then press "Ok".
                                                        After that browse to your profile's page & switch back to Morfix.
                                                    </p>
                                                    <center><h4 class='push'>4. Retry adding</h4></center>
                                                    <div>
                                                        <center><button class="btn btn-primary btn-retry" type="button"><i class="fa fa-refresh"></i> Retry</button></center>
                                                    </div>
                                                    <p>
                                                        Depending on whether your account gets added or not.<br/>
                                                        Repeat the process from Step 3.<br/>
                                                        Try for up to a total of 10 times & if you still can't add, do contact live chat on the bottom right hand corner.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Step 2 -->

                                <!-- Step 3 -->
                                <div class="tab-pane fade fade-right push-30-t push-50" id="validation-step3">

                                </div>
                                <!-- END Step 3 -->
                            </div>
                            <!-- END Steps Content -->

                            <!-- Steps Navigation -->
                            <div class="block-content block-content-mini block-content-full border-t">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <button class="wizard-prev btn btn-modern" type="button"><i class="fa fa-arrow-circle-o-left"></i> Previous</button>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <button class="wizard-next btn btn-primary" type="button">Next <i class="fa fa-arrow-circle-o-right"></i></button>
                                        <button class="wizard-finish btn btn-primary" type="submit"><i class="fa fa-check-circle-o"></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                            <!-- END Steps Navigation -->
                        </form>
                        <!-- END Form -->
                    </div>
                    <!-- END Validation Wizard Wizard -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Add Instagram Profile Modal -->