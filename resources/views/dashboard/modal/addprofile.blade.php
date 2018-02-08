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
                                                        To solve this, approach live chat with the following details::
                                                    </p>
                                                    <center><h4 class='push'>1. Morfix email</h4></center>

                                                    <center><h4 class='push'>2. Instagram username</h4></center>

                                                    <center><h4 class='push'>3. Give us a tentative time that we can contact you</h4></center>

                                                    <center><h4 id="waiting-message" class='push' style="display:none;" >Please hang on...</h4></center>
                                                    <center><h4 id="verify-message" class='push' style="display:none;" >Please copy & paste this link on to another tab/window to verify your account:<br/><a href="#" id="challenge-url"></a></h4></center>
                                                    <center><h4 id="confirm-verify" class='push' style="display:none;">Once you are done please click this button: <button class="btn btn-success" id="relogin-btn">Retry</button></h4></center>
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