<!-- Stats -->
<div class="content bg-white border-b">
    <div class="row items-push text-uppercase">

        <div class="col-xs-6 col-sm-6 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">My Instagram Profiles</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> <b>6</b> quota remaining 
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="Remaining instagram slots you can add in Morfix" type="button"></i></small>
            </div>
            <button class="btn btn-minw btn-rounded btn-primary" data-toggle="modal" data-target="#modal-addprofile" style="margin-top: 10px;" type="button">
                <i class="si si-plus"></i> Add Profile
            </button>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Payable this month
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="Commissions that will be paid out the 25th of this month" type="button"></i>
                </small>
            </div>
            <span class="h2 text-moneygreen animated flipInX" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission_payable }}"></span>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Pending Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Total
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="All commissions that hasn't been paid out" type="button"></i>
                </small></div>
            <span class="h2 text-moneygreen animated flipInX" data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->pending_commission }}"></span>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">Total Commission</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-credit-card"></i> Paid out
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="Total commissions that Morfix has paid out to date." type="button"></i>
                </small></div>
            <span class="h2 text-moneygreen animated flipInX" 
                  data-subject="money" data-toggle="countTo"
                  data-decimals="2" data-to="{{ Auth::user()->all_time_commission }}"
                  >$ {{ number_format(Auth::user()->all_time_commission, 2, '.', ',')  }}</span>
        </div>
        <div class="col-xs-6 col-sm-6 col-lg-4">
            <div class="font-w700 text-gray-darker animated fadeIn">My Leaderboard Ranking</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-trophy"></i> All Time</small></div>
            <span class="h2 font-w300 text-modern animated flipInX">{{ $user_leaderboard_alltime_ranking }}</span>
        </div>
    </div>
</div>
<!-- END Stats -->

<!-- Pop In Modal -->
<div class="modal fade" id="modal-addprofile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Terms &amp; Conditions</h3>
                </div>
                <div class="block-content">
                    <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                    <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> Ok</button>
            </div>
        </div>
    </div>
</div>
<!-- END Pop In Modal -->