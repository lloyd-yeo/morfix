<div class="col-lg-6" style='height: 600px;'>
    <!-- Latest Sales Widget -->
    <div id="leaderboard-container" class="block">
        <div class="block-header">
            <ul class="block-options">
            </ul>
            <h3 class="block-title"><i class="si si-trophy fa-2x"></i> RANKING</h3>
        </div>

        <div class="block-content">
            <div class="pull-t pull-r-l">
                <!-- Block Tabs Justified Alternative Style -->
                <div class="block">
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="btabs-alt-static-justified-weekly">
                            <table class="table table-borderless remove-margin-b remove-margin-t font-s13">
                                <tbody>
                                    @foreach ($competition_leaderboard as $user)
                                        @if ($loop->iteration == 1)
                                        <tr class='bg-modern-lighter'>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user["name"] }}</span>
                                            </td>
                                            <td>
                                                @if ($user["referrals"] > 0)
                                                    <i class='text-primary fa fa-trophy'></i>
                                                @else
                                                @endif

                                            </td>
                                        </tr>
                                        @elseif ($loop->iteration == 2)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user["name"] }}</span>
                                            </td>
                                            <td>
                                                @if ($user["referrals"] > 0)
                                                    <i class='text-primary fa fa-diamond'></i>
                                                @else
                                                @endif
                                                </td>
                                        </tr>
                                        @elseif ($loop->iteration == 3)
                                        <tr>
                                            <td class="font-w700">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user["name"] }}</span>
                                            </td>
                                            <td>
                                                @if ($user["referrals"] > 0)
                                                    <i class='text-primary si si-badge'></i>
                                                @else
                                                @endif

                                            </td>
                                        </tr>
                                        @elseif ($loop->iteration > 10)
                                        @else
                                        <tr>
                                            <td class="font-w600">
                                                <span class='text-primary'>#{{ $loop->iteration }} {{ $user["name"] }}</span>
                                            </td>
                                            <td>
                                                @if ($user["referrals"] > 0)
                                                @else
                                                @endif
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