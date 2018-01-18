@extends('layouts.app')

@section('css')
    @include('settings.css')
    <style>
        button .long{
            font: inherit;
            cursor:pointer;
        }
    </style>
@endsection

@section('sidebar')
    @include('sidebar', ['page' => 'settings'])
@endsection

@section('content')
    <main id="main-container">
        <div class="content bg-gray-lighter">
            <div class="row items-push">
                <div class="col-sm-7">
                    <h1 class="page-heading">
                        <i class="si si-settings"></i> Settings
                        <small> Manage your account settings here.</small>
                    </h1>
                </div>
                {{--@if(Auth::user()->tier > 1)--}}
                <div class="col-sm-4">
                    <h1 class="page-heading">
                        <button class="btn btn-danger pull-right" data-toggle="modal" data-target="#CancelConfirmation"> I want to cancel my subscription</button>
                    </h1>
                </div>
                {{--@endif--}}
            </div>
        </div>

        <div class="content content-boxed">
            <!-- Dynamic Table Full -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">My Subscriptions</h3>
                </div>
                <div class="block-content">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                    <table class="table table-bordered table-striped js-dataTable-subscription">
                        <thead>
                        <tr>
                            <th class="text-center">Subscription</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Subscription Start (GMT+8)</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Subscription End (GMT+8)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                @if ($subscription->plan->id == "0137")
                                    <td class="text-center"><label class="label label-info">Premium</label></td>
                                @elseif ($subscription->plan->id == "MX370")
                                    <td class="text-center"><label class="label label-danger">Pro</label></td>
                                @elseif ($subscription->plan->id == "0297")
                                    <td class="text-center"><label class="label label-primary">Business</label></td>
                                @elseif ($subscription->plan->id == "MX970")
                                    <td class="text-center"><label class="label label-primary">Mastermind</label></td>
                                @elseif ($subscription->plan->id == "0167")
                                    <td class="text-center"><label class="label label-primary">Business</label></td>
                                @elseif ($subscription->plan->id == "0197")
                                    <td class="text-center"><label class="label label-primary">Business</label></td>
                                @elseif ($subscription->plan->id == "0247")
                                    <td class="text-center"><label class="label label-primary">Additional 5
                                            Accounts</label></td>
                                @endif
                                <td class="text-center">{{ Carbon\Carbon::createFromTimestamp($subscription->current_period_start)->diffForHumans() }}</td>
                                <td class="text-center">{{ Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->toDayDateTimeString() }}</td>

                                @if ($subscription->status == "active")
                                    <td class="text-center"><label
                                                class="label label-success">{{ title_case($subscription->status) }}</label>
                                    </td>
                                @elseif ($subscription->status == "trialing")
                                    <td class="text-center"><label
                                                class="label label-success">{{ title_case($subscription->status) }}</label>
                                    </td>
                                @elseif ($subscription->status == "canceled")
                                    <td class="text-center"><label
                                                class="label label-danger">{{ title_case($subscription->status) }}</label>
                                    </td>
                                @elseif ($subscription->status == "past_due")
                                    <td class="text-center"><label
                                                class="label label-danger">{{ title_case($subscription->status) }}</label>
                                    </td>
                                @elseif ($subscription->status == "unpaid")
                                    <td class="text-center"><label
                                                class="label label-danger">{{ title_case($subscription->status) }}</label>
                                    </td>
                                @endif

                                <td class="text-center">
                                    @if ($subscription->status == "unpaid" || $subscription->status == "past_due")
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-success btn-pay-invoice"
                                                    data-sub-id="{{ $subscription->id }}"
                                                    data-invoice-id="{{ $invoices[$subscription->id]->id }}"
                                                    type="button" data-toggle="tooltip" title="Pay Subscription"><i
                                                        class="fa fa-credit-card"></i></button>
                                        </div>
                                    @else
                                        @if ($subscription->cancel_at_period_end === TRUE)
                                            <div>Cancelled</div>
                                        @else
                                            <div class="btn-group">
                                                <button class="btn btn-xs btn-danger btn-cancel-subscription"
                                                        data-sub-id="{{ $subscription->id }}" type="button"
                                                        data-toggle="tooltip" title="Cancel Subscription"><i
                                                            class="fa fa-times"></i></button>
                                            </div>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END Dynamic Table Full -->

            <!-- Dynamic Table Full -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">My Invoices</h3>
                </div>
                <div class="block-content">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                    <table class="table table-bordered table-striped js-dataTable-invoices">
                        <thead>
                        <tr>
                            <th class="text-center">Invoice</th>
                            <th class="text-center"><i class="fa fa-tags"></i> Invoice Plan</th>
                            <th class="text-center"><i class="fa fa-clock-o"></i> Invoice Date (GMT+8)</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($invoices_ != NULL)
                        @foreach ($invoices_->autoPagingIterator() as $invoice)
                            <tr>
                                @foreach ($invoice->lines->data as $invoice_lines)
                                    <td class="text-center">{{ $invoice->id }}</td>

                                    @if ($invoice_lines->plan->id == "0137")
                                        <td class="text-center"><label class="label label-info">Premium</label></td>
                                    @elseif ($invoice_lines->plan->id == "MX370")
                                        <td class="text-center"><label class="label label-danger">Pro</label></td>
                                    @elseif ($invoice_lines->plan->id == "0297")
                                        <td class="text-center"><label class="label label-primary">Business</label></td>
                                    @elseif ($invoice_lines->plan->id == "MX970")
                                        <td class="text-center"><label class="label label-primary">Mastermind</label>
                                        </td>
                                    @elseif ($invoice_lines->plan->id == "0167")
                                        <td class="text-center"><label class="label label-primary">Business</label></td>
                                    @elseif ($invoice_lines->plan->id == "0197")
                                        <td class="text-center"><label class="label label-primary">Business</label></td>
                                    @elseif ($invoice_lines->plan->id == "0247")
                                        <td class="text-center"><label class="label label-primary">Additional 5
                                                Accounts</label></td>
                                    @endif
                                    <td class="text-center">{{ \Carbon\Carbon::createFromTimestamp($invoice->date)->toDayDateTimeString() }}</td>
                                    @if (!$invoice->paid)
                                        <td class="text-center"><label class="label label-danger">Unpaid</label></td>
                                    @else
                                        <td class="text-center"><label class="label label-success">Paid</label></td>
                                    @endif
                                @endforeach

                                <td class="text-center">
                                    @if (!$invoice->paid)
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-success btn-pay-invoice"
                                                    data-invoice-id="{{ $invoice->id }}" type="button"
                                                    data-toggle="tooltip" title="Pay Invoice"><i
                                                        class="fa fa-credit-card"></i></button>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            @if (Auth::user()->paypal == 1)
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Paypal</h3>
                </div>
                <div class="block-content" style="padding-bottom: 50px;">
                    <button id="cancel-paypal-btn" class="btn" data-agreement-id="{{ $agreement_id }}">Cancel Paypal Subscription</button>
                </div>
            </div>
            @endif
            <!-- END Dynamic Table Full -->
            <div id="edit_address"></div>

            @if((Auth::user()->address))
                <div class="block" id="display_address">
                    <div class="block-header">
                        <h3 class="block-title">Personal Address</h3>
                    </div>
                    <div class="block-content">
                        <?= Auth::user()->address ?>
                        <br><br>
                        <button class="btn btn-primary" type="button" onclick="edit_address_button();">Update address</button>
                        <div style="height: 30px;">

                        </div>
                    </div>
                </div>
            @endif
            @if(empty(Auth::user()->address))
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Add Personal Address</h3>
                </div>
                <div class="block-content">
                    <form id="address_form" action="" method="POST">
                        <textarea name="address" id="address" class="form-control"></textarea>
                        <br>
                        <button type="button" onclick="save_address();" class="btn btn-primary">Save Address</button>
                    </form>
                    <div style="height: 30px;">

                    </div>
                </div>
            </div>
            @endif

                <div class="block" id="braintree-null">
                    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
                    <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
                    <div class="block-header">
                        <h3 class="block-title">Update card details</h3>
                    </div>
                    <div class="block-content">
                        <form method="POST" action="/update-details" id="update_card">
                            <div id="dropin-container"></div>
                            <button class="btn btn-primary" id="submit-button" type="button"> Save Updated Card Details</button>
                            <button class="btn btn-success" id="use-old-card" type="button"> Use My Old Card</button>
                        </form>
                        <div style="height: 30px;">

                        </div>
                    </div>
                    <script>
                        // function update_card_details() {
                        //     if(confirm("Are you sure to change your card details?")){
                        //         var formdata = {
                        //           'card_holder' : $('#braintree__card-view-input__cardholder-name').val(),
                        //           'card_number' : $('#').val(),
                        //           'expiration' : $('#expiration').val(),
                        //           'cvv' : $('#cvv').val(),
                        //           'postal_code' : $('#postal-code').val()
                        //         }
                        //         $.ajax({
                        //             type: "POST",
                        //             url: "/update-details",
                        //             dataType: "json",
                        //             success:  function(success){
                        //                 //alert("---"+data);
                        //                 alert("Address successfully added");
                        //                 window.location.reload(true);
                        //             }
                        //         });
                        //     }
                        // }

                        var button = document.querySelector('#submit-button');

                        $(document).ready(function () {

                            @if(session()->has('error'))
                            alert("{{ session('error') }}")
                            @endif

                        });

                        braintree.dropin.create({
                            authorization: '{{ $client_token }}',
                            container: '#dropin-container',

                            card: {
                                cardholderName: {
                                    required: true
                                }
                            },

                        }, function (createErr, instance) {
                            button.addEventListener('click', function () {
                                instance.requestPaymentMethod(function (err, payload) {
                                    // Submit payload.nonce to your server
                                    $("#payment-nonce").val(payload.nonce);
                                    $("#payment-form").submit();
                                });
                            });
                        });
                    </script>
                </div>
            <div class="block" id="braintree-value">
                <div class="block-header">
                    <h3 class="block-title">Update card details</h3>
                </div>
                <div class="block-content">
                        <button class="btn btn-primary" id="show-braintree-null" name="submit" type="submit"> Update Card Details</button>
                    <div style="height: 30px;">

                    </div>
                </div>
            </div>
        </div>

    </main>

<div id="CancelConfirmation" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center">Do you really want to cancel? </h2>
      </div>
      <div class="modal-body">
        <p>Thanks for using Morfix! Hope you appreciated our paid benefits. After you cancel, you'll be downgraded to our free plan.
        This means:</p><br>

        <p> - Suspension of Auto Interactions </p>
        <p> - Suspension of Auto DMs </p>
        <p> - Suspension of Engagement Group benefits (for Business users that are downgrading) </p>
        <p> - Morfix Branding on Image Captions </p>
        <p> - Limited to 1 instagram account (for Business users that are downgrading) </p>
        <br>
        <p> You will be able to use Morfix services that you've paid for until your subscription expires at the end of the current subscription period. </p>

        <p>After that you will still have access to free features with corresponding limitations & branding.</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
        <button type="button" class="btn btn-default pull-right" style="color:red" data-toggle="modal"  data-dismiss="modal" data-target="#QA">Yes, cancel my subscription</button>
      </div>
    </div>

  </div>
</div>

<div id="QA" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center">We're sorry to see you leave. </h2>
      </div>
      <div class="modal-body">
                <section>
                    <div class="wizard">

                        <ul class="nav nav-wizard"  style="display: none;">

                            <li class="active">
                                <a href="#step1" data-toggle="tab"></a>
                            </li>

                            <li class="disabled">
                                <a href="#step2" data-toggle="tab"></a>
                            </li>

                            <li class="disabled">
                                <a href="#step3" data-toggle="tab"></a>
                            </li>

                            <li class="disabled">
                                <a href="#step4" data-toggle="tab"></a>
                            </li>
                            <li class="disabled">
                                <a href="#step5" data-toggle="tab"></a>
                            </li>
                        </ul>

                        <form action="/cancel-subscription" method="POST">
                            <div class="tab-content">
                                <div class="tab-pane active" id="step1">
                                    <center>
                                        <img src="https://images.typeform.com/images/WXhyJGNcF5iF/image/default#.png" data-original="https://images.typeform.com/images/WXhyJGNcF5iF/image/default#.png" style="width: 400px; height: 400px;">
                                        <div style="width:70%;">
                                            <p>
                                                <strong>
                                                    However, would it be okay to get some feedbacks from you so we know how we can hope to serve you better next time?
                                                </strong>
                                            </p>
                                        </div>
                                    </center>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
                                        <button type="button"  class="btn btn-primary pull-right">Yes, Go to first question</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step2">
                                    <center>
                                        <div style="width:100%;">
                                            <p>
                                                <strong>
                                                  1. How long have you been using Morfix?*
                                                    <p></p>
                                                   <button type="button" class="btn btn-default long" style="width: 200px;" onclick="checkValue(this);" value="Less than a week">Less than a week</button>
                                                    &nbsp;&nbsp;
                                                    <button type="button" class="btn btn-default long" style="width: 200px;" onclick="checkValue(this);" value="Less than a month">Less than a month</button>
                                                    <p></p>
                                                    <button type="button" class="btn btn-default long" style="width: 200px;" onclick="checkValue(this);" value="1 - 3 months">1 - 3 months</button>
                                                    &nbsp;&nbsp;
                                                    <button type="button" class="btn btn-default long" style="width: 200px;" onclick="checkValue(this);" value="More than 6 months">More than 6 months</button>
                                                </strong>
                                            </p>
                                        </div>
                                    </center>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
                                        <button type="button"  class="btn btn-primary pull-right" onclick="save_first();">Yes, Go to second question</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step3">
                                        <div style="width:100%;">
                                            <p>
                                                <strong>
                                                    2. What made you cancel Morfix?*
                                            <p></p>
                                            <textarea class="form-control" name="second_question" style="height:30%"></textarea>
                                            </strong>
                                            </p>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
                                        <button type="button"  class="btn btn-primary pull-right" onclick="save_second();">Yes, Go to third question</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step4">
                                         <div style="width:100%;">
                                            <p>
                                                <strong>
                                                    3. What would make you reconsider Morfix again?
                                            <p></p>
                                            <textarea class="form-control" name="third_question" style="height:30%"></textarea>
                                            </strong>
                                            </p>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
                                        <button type="button"  class="btn btn-primary pull-right"onclick="save_third();">Yes, Go to last question</button>
                                    </div>
                                </div>
                                <div class="tab-pane" id="step5">
                                        <div style="width:100%;">
                                            <p>
                                                <strong>
                                                    4. What features would you like to see if you were to come back to Morfix?
                                            <p></p>
                                            <textarea class="form-control" name="fourth_question" style="height:30%"></textarea>
                                            </strong>
                                            </p>
                                        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No, I've change my mind</button>
                                        <button type="submit" class="btn btn-primary pull-right">Yes, cancel my subscription</button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <input type="hidden" name="first_question" id="first_question">
                        </form>
                    </div>
                </section>
            </div>
        </div>

    </div>
    </div>
  </div>
</div>
@endsection

@section('js')
    @include('settings.js')
    <script type="text/javascript">
        $(document).ready(function () {

            @if(!is_null(Auth::user()->braintree_id))
                $('#braintree-null').show();
                $('#braintree-null-button').hide();
                @else
                $('#braintree-null').hide();
                $('#braintree-null-button').show();
            @endif

            $('#show-braintree-null').on('click',function(){
                $('#braintree-null').show();
                $('#braintree-value').hide();
                $('#braintree-null-button').hide();
            })

            $('#use-old-card').on('click',function(){
                $('#braintree-null').hide();
                $('#braintree-value').show();
                $('#braintree-null-button').show();
            })
        });

        function edit_address_button() {
            $('#display_address').hide();
            $('#edit_address').html('<div class="block">\n' +
                '                <div class="block-header">\n' +
                '                    <h3 class="block-title">Update Personal Address</h3>\n' +
                '                </div>\n' +
                '                <div class="block-content">\n' +
                '                    <form id="address_form" action="" method="POST">\n' +
                '                        <textarea name="address" id="address" class="form-control"><?= Auth::user()->address ?></textarea>\n' +
                '                        <br>\n' +
                '                        <button type="button" onclick="save_address();" class="btn btn-primary">Save Update</button>\n' +
                '                    </form>\n' +
                '                    <div style="height: 30px;">\n' +
                '\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '            </div>')
        }

        function save_address() {
            $.ajax({
                type: "POST",
                url: "/settings/address/update",
                dataType: "json",
                data: jQuery("#address_form").serialize(),
                success:  function(success){
                    //alert("---"+data);
                    alert("Address successfully added");
                    window.location.reload(true);
                }
            });
        }

        $(document).ready(function () {

            //Wizard
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

                var $target = $(e.target);

                if ($target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".btn-primary").click(function (e) {

                var $active = $('.wizard .nav-wizard li.active');
                $active.next().removeClass('disabled');
                nextTab($active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }

        function checkValue(ele) {
            //console.log(ele.value);
            $('#first_question').val(ele.value);
        }
    </script>
@endsection
