<div class="col-lg-3">
    <!-- Latest Sales Widget -->
    <div id="leaderboard-container" class="block">
        <div class="block-header">
            <ul class="block-options">
            </ul>
            <h3 class="block-title"><i class="si si-trophy fa-2x"></i> TOP AFFILIATE LEADERBOARD</h3>
        </div>

        <div class="block-content">
            <div class="pull-t pull-r-l">
                <!-- Block Tabs Justified Alternative Style -->
                <div class="block">
                    <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
                        <li class="active">
                            <a href="#btabs-alt-static-justified-weekly"><i class="si si-clock"></i> Weekly</a>
                        </li>
                        <li>
                            <a href="#btabs-alt-static-justified-alltime"><i class="si si-calendar"></i> All Time</a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="btabs-alt-static-justified-weekly">
                            <table class="table table-borderless remove-margin-b remove-margin-t font-s13">
                                <tbody>
                                    @foreach ($leaderboard_weekly as $user)
                                        @if ($loop->iteration == 1)
                                        <tr class='bg-modern-lighter'>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary fa fa-trophy'></i></td>
                                        </tr>
                                        @elseif ($loop->iteration == 2)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary fa fa-diamond'></i></td>
                                        </tr>
                                        @elseif ($loop->iteration == 3)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary si si-badge'></i></td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="font-w600">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="btabs-alt-static-justified-alltime">
                            <table class="table table-borderless remove-margin-b remove-margin-t font-s13">
                                <tbody>
                                    @foreach ($leaderboard_alltime as $user)
                                        @if ($loop->iteration == 1)
                                        <tr class='bg-modern-lighter'>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary fa fa-trophy'></i></td>
                                        </tr>
                                        @elseif ($loop->iteration == 2)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary fa fa-diamond'></i></td>
                                        </tr>
                                        @elseif ($loop->iteration == 3)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                            <td><i class='text-primary si si-badge'></i></td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="font-w600">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user->name }}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END Block Tabs Justified Default Style -->
            </div>
        </div>
    </div>
    <!-- END Latest Sales Widget -->
</div>