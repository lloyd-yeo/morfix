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
                    <i class="si si-picture"></i>  Direct Message Logs<small> View your pending direct messages!</small>
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
                            <tr id='dm-{{ $dm_job }}'>
                                <td>{{ Carbon\Carbon::parse($dm_job->time_to_send)->toDayDateTimeString() }}</td>
                                <td class="text-center">
                                    @if ($dm_job->fulfilled == 0)
                                    <label class="label label-primary">Pending</label>
                                    @elseif ($dm_job->fulfilled == 1)
                                    <label class="label label-success">Sent!</label>
                                    @elseif ($dm_job->fulfilled == 2)
                                    <label class="label label-default">Failed</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->recipient_username }}
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->recipient_fullname }}
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->message }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-danger btn-cancel-job" 
                                                data-job-id="{{ $dm_job->job_id }}" type="button" data-toggle="tooltip" title="Cancel Job"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
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
                            <tr>
                                <td>{{ Carbon\Carbon::parse($dm_job->time_to_send)->toDayDateTimeString() }}</td>
                                <td class="text-center">
                                    @if ($dm_job->fulfilled == 0)
                                    <label class="label label-primary">Pending</label>
                                    @elseif ($dm_job->fulfilled == 1)
                                    <label class="label label-success">Sent!</label>
                                    @elseif ($dm_job->fulfilled == 2)
                                    <label class="label label-default">Failed</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->recipient_username }}
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->recipient_fullname }}
                                </td>
                                <td class="text-center">
                                    {{ $dm_job->message }}
                                </td>
                            </tr>
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