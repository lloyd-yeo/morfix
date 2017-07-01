<!-- Add Instagram Profile Modal -->
<div class="modal fade" id="modal-checkpoint" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin modal-lg">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title"><i class='fa fa-instagram'></i> RETRY VERIFICATION</h3>
                </div>
                <div class="block-content">
                    <div class="block">
                        <!-- Form -->
                            <!-- Steps Content -->
                            <div class="block-content">
                                <!-- Step 2 -->
                                <div class="push-30-t push-50">
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
                                                        Go to <a target='_blank' href='http://www.instagram.com'>www.instagram.com</a> & login with the account that you are trying to add to Morfix.
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
                            </div>
                            <!-- END Steps Content -->

                            <!-- Steps Navigation -->
<!--                            <div class="block-content block-content-mini block-content-full border-t">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <button class="wizard-prev btn btn-modern" type="button"><i class="fa fa-arrow-circle-o-left"></i> Previous</button>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <button class="wizard-next btn btn-primary" type="button">Next <i class="fa fa-arrow-circle-o-right"></i></button>
                                        <button class="wizard-finish btn btn-primary" type="submit"><i class="fa fa-check-circle-o"></i> Submit</button>
                                    </div>
                                </div>
                            </div>-->
                            <!-- END Steps Navigation -->
                    </div>
                    <!-- END Validation Wizard Wizard -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Add Instagram Profile Modal -->