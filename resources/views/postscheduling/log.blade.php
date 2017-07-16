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
                                <th class="text-center"><i class="fa fa-clock-o"></i> Date To Post (GMT+8)</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Log</th>
                                <th class="text-center" style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <tr id="schedule-{{ $schedule->schedule_id }}">
                                @if ($schedule->date_to_post !== NULL)
                                <td>{{ Carbon\Carbon::parse($schedule->date_to_post)->toDayDateTimeString() }}</td>
                                @else
                                <td>No date defined.</td>
                                @endif
                                <td class="text-center">
                                    @if ($schedule->posted == 0)
                                    <label class="label label-primary">Pending</label>
                                    @elseif ($schedule->posted == 1)
                                    <label class="label label-success">Posted Successfully!</label>
                                    @elseif ($schedule->posted == 2)
                                    <label class="label label-default">Failed</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($schedule->log == NULL)
                                    Your post is pending posting.
                                    @elseif ($schedule->failure_msg !== NULL)
                                    {{ $schedule->failure_msg }}
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