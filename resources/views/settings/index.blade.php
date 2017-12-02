@extends('layouts.app')

@section('css')
    @include('settings.css')
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


            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Update My Card Details</h3>
                </div>
                <div class="block-content">
                    <form action="/settings/cards/update" method="POST">
                        <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="pk_live_WrvnbbOwMxU7FwZzaoTdaUpa"
                                data-image="https://morfix.co/app/assets/img/logo/mx-black-crop.png"
                                data-name="Morfix.co"
                                data-panel-label="Update Card Details"
                                data-label="Update Card Details"
                                data-allow-remember-me="true"
                                data-locale="auto"
                                data-email="{{ Auth::user()->email }}">
                        </script>
                    </form>
                    <div style="height: 30px;">

                    </div>
                </div>
            </div>

        </div>

    </main>
@endsection

@section('js')
    @include('settings.js')
@endsection