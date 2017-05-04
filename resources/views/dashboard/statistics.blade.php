<!-- Stats -->
<div class="content bg-white border-b">
    <div class="row items-push text-uppercase">
        
        <div class="col-xs-6 col-sm-2">
            <div class="block-content block-content-full bg-modern">
                <i class="si si-crop fa-4x text-white"></i>
                <div class="font-w600 text-white-op push-15-t">Crop</div>
            </div>
        </div>
        
<!--        <div class="col-xs-6 col-sm-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> This month</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></span>
        </div>-->
        <div class="col-xs-6 col-sm-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Next Month</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX">$ 0.00</span>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Total Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Consolidated</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX"
                  data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></span>
        </div>
        <div class="col-xs-6 col-sm-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Total Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-credit-card"></i> Paid out</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX" 
                  data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->total_commission }}"
                  >$ {{ number_format(Auth::user()->total_commission, 2, '.', ',')  }}</span>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="font-w700 text-gray-darker animated fadeIn">Leaderboard Ranking</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-trophy"></i> All Time</small></div>
            <span class="h2 font-w300 text-modern animated flipInX">{{ $user_leaderboard_alltime_ranking }}</span>
        </div>
    </div>
</div>
<!-- END Stats -->