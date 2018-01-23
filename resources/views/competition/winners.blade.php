<div class="block">
    <div class="block-header">
        <h3 class="block-title"><center><i class="si si-trophy fa-2x"></i>WINNERS RANKING</center></h3>
    </div>
    <div class="block-content tab-content"  style="width:30%">
        <table class="table table-borderless remove-margin-b remove-margin-t font-s13">
            @foreach ($competition_winners as $winner)
                @if ($loop->iteration == 1)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 2)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 3)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 4)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 5)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 6)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 7)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 8)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 9)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @elseif ($loop->iteration == 10)
                    <tr>
                        <td class="font-w700">
                            <span class='text-primary'>#{{ $loop->iteration }} {{ $winner->name }}</span>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>