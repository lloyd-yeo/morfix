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
                    <i class="si si-settings"></i> Settings <small> Manage your account settings here.</small>
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
                            <td class="text-center"><label class="label label-primary">Additional 5 Accounts</label></td>
                            @endif
                            
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-danger" data-sub-id="{{ $subscription->id }}" type="button" data-toggle="tooltip" title="Cancel Subscription"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>
</main>
@endsection

@section('js')
@include('settings.js')
@endsection