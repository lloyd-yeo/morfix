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
                                <label for="image-caption">Your image caption</label>
                            </div>
                        </div>
                    </div>
                    
                    @if (Auth::user()->user_tier > 1)
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="form-material form-material-primary">
                                <textarea class="js-maxlength form-control" id="gallery-first-comment-txt" 
                                          name="first-comment" rows="7" maxlength="2000"
                                          placeholder="Type in the first comments here..."></textarea>
                                <label for="first-comment">Your first comments</label>
                            </div>
                            <div class="help-block text-left">
                                <!--                                        <b>EMOJI</b>
                                                                        <br/>
                                                                        Press ":" to bring up emojis.-->
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <div class="col-md-8">
                            <div class="js-datetimepicker form-material input-group date" data-show-today-button="true" data-show-clear="true" data-show-close="true" data-side-by-side="true">
                                <input class="form-control" type="text" id="gallery-schedule-date" name="schedule-date" placeholder="Choose a date..">
                                <label for="schedule-date">Schedule Date (GMT +8)</label>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>