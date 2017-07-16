@extends('layouts.app')

@section('css')
@include('postscheduling.log.css')
@endsection

@section('sidebar')
@include('sidebar', ['page' => 'postscheduling'])
@endsection

@section('content')
<main id="main-container">
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    <i class="si si-picture"></i>  Post Scheduling Logs<small> View your scheduled post!</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="content content-boxed">
        <div class="row font-s13">
            
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Scheduled Posts</h3>
                </div>
                
                <div class="block-content">
                    <table class="table table-bordered table-striped js-dataTable-schedule">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%;">Log ID</th>
                                <th class="text-center" style="width: 25%;"><i class="fa fa-clock-o"></i> Date To Post (GMT+8)</th>
                                <th class="text-center" style="width: 20%;">Status</th>
                                <th class="text-center" style="width: 30%;">Log</th>
                                <th class="text-center" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr id="schedule-{{ $schedule->schedule_id }}">
                                <td>{{ $schedule->schedule_id }}</td>
                                @if ($schedule->date_to_post !== NULL)
                                <td class="text-center">{{ Carbon\Carbon::parse($schedule->date_to_post)->toDayDateTimeString() }}</td>
                                @else
                                <td class="text-center"><label class="label label-danger">No date defined.</label></td>
                                @endif
                                <td class="text-center">
                                    @if ($schedule->posted == 0)
                                    <label class="label label-primary">Pending</label>
                                    @elseif ($schedule->posted == 1)
                                    <label class="label label-success">Success!</label>
                                    @elseif ($schedule->posted == 2)
                                    <label class="label label-default">Failed</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($schedule->date_to_post === NULL)
                                    <label class="label label-danger">We are unable to post because you have not defined the date to post.</label>
                                    @elseif ($schedule->posted == 0)
                                    <label class="label label-primary">Your post is pending posting.</label>
                                    @elseif ($schedule->posted == 1)
                                    <label class="label label-success">Your post has been successfully uploaded on to Instagram!</label>
                                    @elseif ($schedule->posted == 2 && $schedule->failure_msg !== NULL)
                                    <label class="label label-primary">{{ $schedule->failure_msg }}</label>
                                    @else
                                    {{ $schedule->log }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-default btn-view-schedule" data-schedule-id="{{ $schedule->schedule_id }}">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </button>
                                        @if ($schedule->posted == 0)
                                        <button class="btn btn-xs btn-danger btn-cancel-schedule" 
                                                data-schedule-id="{{ $schedule->schedule_id }}" type="button" data-toggle="tooltip" title="Cancel scheduling"><i class="fa fa-times"></i></button>
                                        @endif        
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @include('postscheduling.log.modal')
    
</main>
@endsection

@section('js')
@include('postscheduling.log.js')
@endsection