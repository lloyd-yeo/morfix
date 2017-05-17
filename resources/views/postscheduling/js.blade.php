<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/emojione/2.2.7/lib/js/emojione.min.js"></script>
<script src="{{ asset('assets/js/plugins/jquery-textautocomplete/jquery.textcomplete.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/auto_interaction_settings.js') }}"></script>
<script src="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.js') }} "></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    // Init page helpers (Select2 + Tags Inputs plugins)
    App.initHelpers(['select2', 'tags-inputs', 'slimscroll', 'magnific-popup']);
    
    initCommentsEmojiAutocomplete();
    
    initValidationMaterial();
    
    $("#img-upload").dropzone({
        url: "/post-scheduling/add",
        addRemoveLinks : true,
        maxFilesize: 5,
        dictDefaultMessage: '<span class="text-center"><span class="font-lg visible-xs-block visible-sm-block visible-lg-block"><span class="font-lg"><i class="fa fa-caret-right text-danger"></i> Drop files <span class="font-xs">to upload</span></span><span>&nbsp&nbsp<h4 class="display-inline"> (Or Click)</h4></span>',
        dictResponseError: 'Error uploading file!',
        headers: {
            'X-CSRFToken': $('meta[name="token"]').attr('content')
        }
    });
});
</script>