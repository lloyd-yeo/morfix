<!-- Add Instagram Profile Modal -->
<div class="modal fade" id="modal-addprofile" tabindex="-1" role="dialog" aria-hidden="true">
    <input type="text" id="active-request" style="display:none">
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

                                    <div class="col-sm-8 col-sm-offset-2" style="font-size: 12px;">
                                        <center>
                                            <h3>READ THIS FIRST</h3>

                                            <p>Hi Morfix User,</p>

                                            <p>With Instagram latest API, some of you could get into a verfication loop. Which means you can't link your Instagram profile to our system. In order to smoothen the process, if you are unable to verify, there will be a button on the next page to schedule a 5 minute session with our LIVE support on telegram.
                                            <br/>
                                            Our support will then process to help you verify your account at the timing you choose.
                                            <br/>
                                            <b>WE'LL NOT BEGIN YOUR SUBSCRIPTION UNTIL YOUR FIRST PROFILE IS ADDED.</b>
                                            <br/>So you do not have to worry about paying for time you didn't use. Thank you for choosing Morfix :)</p>
                                        </center>
                                    </div>

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
                                                        To add your profile we will need you to assist us with the following:
                                                    </p>
                                                    <center>
                                                        <h4 class='push'>1. Join our telegram group (if you do not have one, please create an account) below:</h4>
                                                        <br/>
                                                        <a href="https://t.me/joinchat/Cm-Skw8JCqn7RfOKPH2SvA" target="_blank">https://t.me/joinchat/Cm-Skw8JCqn7RfOKPH2SvA</a>
                                                        <br/>
                                                    </center>

                                                    <center>
                                                        <h4 class='push'>2. Set-up an appointment with us over here:</h4>
                                                        <br/>
                                                        <a href="https://morfix.youcanbook.me" target="_blank">https://morfix.youcanbook.me</a>
                                                        <br/>
                                                    </center>

                                                    <center>
                                                        <h4 class='push'>3. Come online on Telegram during the appointed time & our friendly Morfix operators will assist you to add in your account!</h4>
                                                    </center>
                                                    {{--<center><h4 id="waiting-message" class='push' style="display:none;" >Please hang on...</h4></center>--}}
                                                    {{--<center><h4 id="verify-message" class='push' style="display:none;" >Please copy & paste this link on to another tab/window to verify your account:<br/><a href="#" style="font-size: 11px;" target="_blank" id="challenge-url"></a></h4></center>--}}
                                                    {{--<center><h4 id="confirm-verify" class='push' style="display:none;">Once you are done please click this button: <a class="btn btn-success" id="relogin-btn">Retry</a></h4></center>--}}
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