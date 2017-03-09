<!-- Stats -->
<div class="content bg-white border-b">
    <div class="row items-push text-uppercase">
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> This month</small></div>
            <span class="h2 font-w700 text-lightred animated flipInX">$ {{ number_format(Auth::user()->pending_commission, 2, '.', ',')  }}</span>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Next Month</small></div>
            <span class="h2 font-w700 text-lightred animated flipInX">$ 0.00</span>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Total Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-credit-card"></i> Paid out</small></div>
            <span class="h2 font-w700 text-lightred animated flipInX">$ {{ number_format(Auth::user()->total_commission, 2, '.', ',')  }}</span>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Leaderboard Ranking</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-trophy"></i> All Time</small></div>
            
            <?php $in_leaderboard = false; ?>
            
            @foreach ($leaderboard_alltime as $indexKey => $user)
            
                @if ( $user->email == Auth::user()->email )
                <?php $in_leaderboard = true; ?>
                <span class="h2 font-w300 text-primary animated flipInX"># {{ $loop->iteration }}</span>
                @endif
                
            @endforeach
            
            @if (!$in_leaderboard)
            <span class="h2 font-w300 text-muted">Unranked</span>
            @endif
        </div>
    </div>
</div>
<!-- END Stats -->