<div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #FF6A5C;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-white">SIGN UP NOW!</h4>
            </div>

            <div id="modal-body" class="modal-body">
                
                <div class="form-group" style="margin-bottom: 10px; ">
                    <label>Select your plan:</label>
                    <select id="plan-dropdown" name="plan_dropdown" class="plan-dropdown form-control">
                        <option value="1">Premium ($37/mth)</option>
                        <option value="2">Pro ($370/yr) - Get 2 months free!</option>
                    </select>
                </div>
                
                <div class="row col-lg-12" style="margin-left: auto; margin-right: auto; padding-left: 0px; padding-right: 0px;">
                    <div id="premium-pkg" class="dfd_col-tablet-6 columns three dfd_col-tabletop-6 dfd_col-laptop-6 dfd_col-mobile-12">
                        <div class="wpb_wrapper">
                            <div class="dfd-pricing-cover cr-animate-gen animation-done" data-animate-type="transition.slideLeftIn" style="opacity: 1; display: block; transform: translateX(0px); padding-top: 0px;">
                                <div class="ult_pricing_table_wrap ult_design_1  ult_hot ">
                                    <div class="ult_pricing_table" style=""><div class="top-part" style="background-color:#E14658;"><span class="inscription-hot">hot!</span>
                                            <div class="ult_pricing_heading">
                                                <h3 class="box-name" style="font-size:40px;">PREMIUM<br/>PACKAGE</h3>
                                                <h5 class="subtitle" style="color:white;">Main features</h5></div><!--ult_pricing_heading--><div class="ult_price_body_block">
                                                <div class="ult_price_body">
                                                    <div class="ult_price">
                                                        <span class="price-value" style="">$</span>
                                                        <span class="ult_price_figure" style="">37</span><span class="ult_price_term" style="">mo.</span></div>
                                                    <div class="price-description subtitle" style="color:white;">Unlock the full potential of Morfix</div>
                                                </div>
                                            </div><!--ult_price_body_block-->
                                        </div><!--ult_top-part--><div class="bottom-part">
                                            <div class="ult_price_features" style="padding-top: 10px; font-weight: bold;">
                                                <p>No Morfix.co branding</p>
                                                <p>Full speed</p>
                                                <p>High Priority Support</p>
                                                <p>Instagram Affiliate Training</p>
                                                <p>Auto Interaction (Like, Comment Follow, Unfollow)</p>
                                                <p>Greet New Followers With Direct Message</p>
                                                <p>Unlimited Scheduled Posts</p>
                                                <p>First Comment Function</p>
                                                <p>Private Facebook Group</p>
                                            </div><!--ult_price_features-->
                                        </div><!--ult_bottom-part--><div class="ult_clr"></div>
                                    </div><!--pricing_table-->
                                </div><!--pricing_table_wrap-->
                            </div><!--cover-->
                        </div>
                    </div>

                    <div style="display:none;" id="pro-pkg" class="dfd_col-tablet-6 columns three dfd_col-tabletop-6 dfd_col-laptop-6 dfd_col-mobile-12">
                        <div class="wpb_wrapper">
                            <div class="dfd-pricing-cover cr-animate-gen animation-done" data-animate-type="transition.slideLeftIn" style="opacity: 1; display: block; transform: translateX(0px); padding-top: 0px;">
                                <div class="ult_pricing_table_wrap ult_design_1  ult_hot ">
                                    <div class="ult_pricing_table" style=""><div class="top-part" style="background-color:#E14658;"><span class="inscription-hot">hot!</span>
                                            <div class="ult_pricing_heading">
                                                <h3 class="box-name" style="font-size:40px;">PRO<br/>PACKAGE</h3>
                                                <h5 class="subtitle" style="color:white;">Save & learn!</h5></div><!--ult_pricing_heading--><div class="ult_price_body_block">
                                                <div class="ult_price_body">
                                                    <div class="ult_price">
                                                        <span class="price-value" style="">$</span>
                                                        <span class="ult_price_figure" style="">370</span><span class="ult_price_term" style="">yr.</span></div>
                                                    <div class="price-description subtitle" style="color:white;">We teach YOU how to turn your Instagram account into a 6 figure money making tool!</div>
                                                </div>
                                            </div><!--ult_price_body_block-->
                                        </div><!--ult_top-part--><div class="bottom-part">
                                            <div class="ult_price_features" style="padding-top: 10px; font-weight: bold;">
                                                <p>Everything from Premium!</p>
                                                <p>2 months of <b>FREE</b> premium functions</p>
                                                <p>EXCLUSIVE access to our 6 figures Instagram System training videos!</p>
                                            </div><!--ult_price_features-->
                                            <!--ult_price_link--></div><!--ult_bottom-part--><div class="ult_clr"></div>
                                    </div><!--pricing_table-->
                                </div><!--pricing_table_wrap-->
                            </div><!--cover-->
                        </div>
                    </div>

                    <div id="payment-detail-div" class="dfd_col-tablet-6 columns three dfd_col-tabletop-6 dfd_col-laptop-6 dfd_col-mobile-12">
                        <div class="wpb_wrapper">
                            <div class='block'>
                                <div class='block-header'>
                                    <h3 class="block-title" style='font-size: 24px;'>Your account details:</h3>
                                </div>
                                <div class="block-content block-content-full">
                                    <form id="payment-form" class="form-horizontal push-10-t" onsubmit="event.preventDefault();" method="post" action="{{ url('vsl/signup/cc') }}">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material form-material-primary ">
                                                    <input class="form-control" type="text" 
                                                           id="signup-email"  name="email">
                                                    <label for="signup-email">Email:</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material form-material-primary ">
                                                    <input class="form-control" type="text" 
                                                           id="signup-name"  name="name">
                                                    <label for="signup-name">Name:</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material form-material-primary ">
                                                    <input class="form-control" type="password" 
                                                           id="signup-pw"  name="pw">
                                                    <label for="signup-pw">Password:</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material form-material-primary ">
                                                    <input class="form-control" type="password" 
                                                           id="signup-pw2"  name="pw2">
                                                    <label for="signup-pw2">Confirm Password:</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material form-material-primary ">
                                                    <select id="payment-method-dropdown" name="payment_method_dropdown" class="payment-method-dropdown form-control">
                                                        <option value="1">Pay by Debit/Credit Card</option>
                                                        <option value="2">Pay by Paypal</option>
                                                    </select>
                                                    <label for="payment-method-dropdown">Select Payment Method</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="stripe-card-group" class="form-group">
                                            <div class="col-sm-12">
                                                <label for="card-element">
                                                    Credit or Debit Card Number
                                                </label>
                                                <div id="card-element">
                                                    <!-- a Stripe Element will be inserted here. -->
                                                </div>

                                                <!-- Used to display form errors -->
                                                <div id="card-errors"></div>
                                            </div>
                                        </div>
                                        <div id="stripe-logo" class="form-group" style="margin-bottom:0px;">
                                            <div class='col-sm-12'>
                                                <div class='pull-right' style="margin-top: 5px;"><img style='height:30px;' src="../assets/img/logo/powered-by-stripe-dark.png" /></div>
                                            </div>
                                        </div>
                                        <div id="stripe-card-btn" class='form-group'>
                                            <div class='col-sm-12'>
                                                <div class="form-material form-material-primary">
                                                    <button class="pull-right btn btn-sm btn-primary" type="submit">Make Payment</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="paypal-group" class="form-group" style="display:none;">
                                            <div class="col-sm-12">
                                                <!-- PayPal Logo -->
                                                <a id="paypal-btn" href="{{ url('vsl/signup/paypal') }}">
                                                    <img src="https://www.paypalobjects.com/webstatic/en_AU/i/buttons/btn_paywith_primary_s.png" alt="Pay with PayPal" />
                                                </a>
                                                <!-- PayPal Logo -->
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- -->

                <div class="modal-footer centered">
                </div>

            </div>
        </div>
    </div>
</div>