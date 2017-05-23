<!-- Apps Modal -->
<!-- Opens from the button in the header -->
<div class="modal fade" id="upgrade-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-lg modal-dialog modal-dialog-top">
        <div class="modal-content">
            <!-- Apps Block -->
            <div class="block block-themed block-transparent">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Upgrade your account now!</h3>
                </div>
                <div class="block-content">
                    <div class='row'>
                        @if (Auth::user()->tier == 1)
                        <!-- Warning Alert -->
                        <div class='col-lg-12'>
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h3 class="font-w300 push-15"><i class='fa fa-info-circle'></i> Free Trial</h3>
                                <p>You are currently on the <a class="alert-link" href="javascript:void(0)">free trial</a> version of Morfix!</p>
                                <p>Upgrade below to enjoy more features!</p>
                            </div>
                        </div>
                        <!-- END Warning Alert -->
                        @endif
                    </div>
                    <div class="row text-center">
                        @if (Auth::user()->tier == 1)
                        @include('payment.table.premium')
                        @include('payment.table.pro')
                        @elseif (Auth::user()->tier == 2)
                        @include('payment.table.pro')
                        @include('payment.table.business')
                        @elseif (Auth::user()->tier == 3)
                        @include('payment.table.business')
                        @include('payment.table.mastermind')
                        @elseif (Auth::user()->tier == 12)
                        @include('payment.table.pro')
                        @include('payment.table.mastermind')
                        @elseif (Auth::user()->tier == 22)
                        @include('payment.table.pro')
                        @endif
                    </div>
                </div>
            </div>
            <!-- END Apps Block -->
        </div>
    </div>
</div>
<!-- END Apps Modal -->