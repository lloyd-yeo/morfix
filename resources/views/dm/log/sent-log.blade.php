<tr>
    {{--<td class="text-center">{{ Carbon\Carbon::parse($dm_job->time_to_send)->toDayDateTimeString() }}</td>--}}
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