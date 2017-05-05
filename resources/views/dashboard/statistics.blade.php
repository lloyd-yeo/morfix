<!-- Stats -->
<div class="content bg-white border-b">
    <div class="row items-push text-uppercase">

        <div class="col-xs-6 col-sm-6 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">My Instagram Profiles</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> <b>6</b> quota remaining</small></div>
            <button class="btn btn-minw btn-rounded btn-primary" style="margin-top: 10px;" type="button"><i class="si si-plus"></i> Add Profile</button>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Payable this month</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission_payable }}"></span>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Total</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></span>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Total Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-credit-card"></i> Paid out</small></div>
            <span class="h2 font-w700 text-moneygreen animated flipInX" 
                  data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->all_time_commission }}"
                  >$ {{ number_format(Auth::user()->all_time_commission, 2, '.', ',')  }}</span>
        </div>
        
        <!--        <div class="col-xs-6 col-sm-2">
                    <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
                    <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Next Month</small></div>
                    <span class="h2 font-w700 text-moneygreen animated flipInX">$ 0.00</span>
                </div>-->
        <!--        <div class="col-xs-6 col-sm-3">
                    <div class="font-w700 text-gray-darker animated fadeIn">Total Pending Commission</div>
                    <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Consolidated</small></div>
                    <span class="h2 font-w700 text-moneygreen animated flipInX"
                          data-subject="money" data-toggle="countTo"
                          data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></span>
                </div>-->
        <!--        <div class="col-xs-6 col-sm-2 col-lg-3">
                    <div class="font-w700 text-gray-darker animated fadeIn">Total Commission</div>
                    <div class="text-muted animated fadeIn"><small><i class="si si-credit-card"></i> Paid out</small></div>
                    <span class="h2 font-w700 text-moneygreen animated flipInX" 
                          data-subject="money" data-toggle="countTo"
                          data-decimals="2" data-to="{{ Auth::user()->total_commission }}"
                          >$ {{ number_format(Auth::user()->total_commission, 2, '.', ',')  }}</span>
                </div>-->
        <div class="col-xs-6 col-sm-6 col-lg-offset-1 col-lg-3">
            <div class="font-w700 text-gray-darker animated fadeIn">My Leaderboard Ranking</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-trophy"></i> All Time</small></div>
            <span class="h2 font-w300 text-modern animated flipInX">{{ $user_leaderboard_alltime_ranking }}</span>
        </div>
    </div>
</div>
<!-- END Stats -->