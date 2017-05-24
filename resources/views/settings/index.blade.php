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
                            <th>Subscription</th>
                            <th class="text-center" style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $sub_id => $plan_id)
                        <tr>
                            @if ($plan_id == "0137")
                            <td class="text-center">Premium</td>
                            @elseif ($plan_id == "0297")
                            <td class="text-center">Business</td>
                            @elseif ($plan_id == "0247")
                            <td class="text-center">Business (additional 5 accounts)</td>
                            @elseif ($plan_id == "MX370")
                            <td class="text-center">Pro</td>
                            @elseif ($plan_id == "MX970")
                            <td class="text-center">Mastermind</td>
                            @endif
                            
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title="Cancel Subscription"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-center">2</td>
                            <td class="font-w600">Rebecca Gray</td>
                            <td class="hidden-xs">client2@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-primary">Personal</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td class="font-w600">Evelyn Willis</td>
                            <td class="hidden-xs">client3@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-danger">Disabled</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td class="font-w600">Laura Bell</td>
                            <td class="hidden-xs">client4@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td class="font-w600">Jack Greene</td>
                            <td class="hidden-xs">client5@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-warning">Trial</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">6</td>
                            <td class="font-w600">John Parker</td>
                            <td class="hidden-xs">client6@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-warning">Trial</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">7</td>
                            <td class="font-w600">Judy Alvarez</td>
                            <td class="hidden-xs">client7@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-success">VIP</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">8</td>
                            <td class="font-w600">Scott Ruiz</td>
                            <td class="hidden-xs">client8@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-success">VIP</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">9</td>
                            <td class="font-w600">Tiffany Kim</td>
                            <td class="hidden-xs">client9@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">10</td>
                            <td class="font-w600">Joshua Munoz</td>
                            <td class="hidden-xs">client10@example.com</td>
                            <td class="hidden-xs">
                                <span class="label label-info">Business</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Edit Client"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-xs btn-default" type="button" data-toggle="tooltip" title="Remove Client"><i class="fa fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
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