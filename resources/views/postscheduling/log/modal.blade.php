<div class="modal fade" id="modalViewSchedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin modal-lg">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title"><i class='fa fa-instagram'></i> SCHEDULED POST</h3>
                </div>
            </div>
            <div class="block-content">
                <form class="form-horizontal push-10">
                    <span id="meta-id"></span>
                    
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="form-material form-material-primary">
                                <label for="image-caption">Scheduled Image</label>
                                <br/>
                                <img src='' id='schedule-img'>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="form-material form-material-primary">
                                <textarea class="js-maxlength form-control"
                                          id="schedule-caption" rows="7" maxlength="2000"
                                          placeholder="Type in the first comments here..."></textarea>
                                <label for="schedule-caption">Captions</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <span id="schedule-date-label"><i class="fa fa-calendar"></i> This Post is Scheduled At (GMT +8)</span>
                            <br/>
                            <span id="schedule-date" style='font-weight:bold;'>2017-01-01 00:00:00</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>