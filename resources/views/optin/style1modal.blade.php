<div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #FF6A5C;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-white text-center">Instagram Loophole Income System!</h4>
            </div>

            <div id="modal-body" class="modal-body">
                
                <div class="row col-lg-12" style="margin-left: auto; margin-right: auto; padding-left: 0px; padding-right: 0px;">
                    
                    <div id="payment-detail-div" class="dfd_col-tablet-12 columns three dfd_col-tabletop-12 dfd_col-laptop-12 dfd_col-mobile-12">
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
