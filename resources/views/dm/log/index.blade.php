@extends('layouts.app')

@section('css')
@include('dm.log.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'dm'])
@endsection

@section('content')
<main id="main-container">

    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-envelope"></i>  Direct Message Logs<small> View your pending direct messages!</small>
                </h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        <div class="row font-s13">

            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Pending DMs</h3>
                </div>

                <div class="block-content">
                    <button class='btn btn-danger push-20' id='delete-all-pending-btn' data-insta-id='{{ $ig_profile->id }}'>
                        <i class='fa fa-times'></i> Delete All Pending Jobs
                    </button>
                    <table class="table table-bordered table-striped js-dataTable-dmJob">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fa fa-clock-o"></i> Date To Send (GMT+8)</th>
                                <th class="text-center">Fulfilled</th>
                                <th class="text-center">Recipient</th>
                                <th class="text-center">Recipient Full Name</th>
                                <th class="text-center">Message</th>
                                <th class="text-center" style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pending_dm_jobs as $dm_job)
                            @include('dm.log.pending-log', [ 'dm_job' => $dm_job ])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Sent DMs</h3>
                </div>

                <div class="block-content">
                    <table class="table table-bordered table-striped js-dataTable-sentDmJob">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fa fa-clock-o"></i> Date To Post (GMT+8)</th>
                                <th class="text-center">Fulfilled</th>
                                <th class="text-center">Recipient</th>
                                <th class="text-center">Recipient Full Name</th>
                                <th class="text-center">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sent_dm_jobs as $dm_job)
                            @include('dm.log.sent-log', [ 'dm_job' => $dm_job ])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@section('js')
@include('dm.log.js')
@endsection