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
        <div class="row font-s13">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
            <table class="table table-bordered table-striped js-dataTable-subscription">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>Subscription</th>
                        <th class="hidden-xs">Email</th>
                        <th class="hidden-xs" style="width: 15%;">Access</th>
                        <th class="text-center" style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="font-w600">Roger Hart</td>
                        <td class="hidden-xs">client1@example.com</td>
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
                        <td class="text-center">1</td>
                        <td class="font-w600">Roger Hart</td>
                        <td class="hidden-xs">client1@example.com</td>
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
</main>
@endsection

@section('js')
@include('settings.js')
@endsection