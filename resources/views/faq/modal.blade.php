<div class="modal fade" id="qna-{{ $qna->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin modal-lg">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-modern">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title"><i class='fa fa-info'></i> FAQ</h3>
                </div>
                <div class="block-content">
                    <!-- Form -->
                    <!-- Step 2 -->
                    <div class="push-30-t push-50">
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-material">
                                    <div class='block'>
                                        <center><h3 class='push text-modern' id='question'></h3></center>
                                        <p class='lead' id='answer'>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Step 2 -->

                    <!-- Steps Navigation -->
                    <div class="block-content block-content-mini block-content-full border-t">
                        <div class="row">
                        </div>
                    </div>
                    <!-- END Steps Navigation -->
                </div>
            </div>
        </div>
    </div>
</div>