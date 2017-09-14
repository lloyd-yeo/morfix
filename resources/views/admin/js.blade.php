<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!--<script src="{{ asset('assets/js/pages/auto_interaction_settings.js') }}"></script>-->
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery(function () {
    // Init page helpers (Select2 + Tags Inputs plugins)
    App.initHelpers(['select2', 'tags-inputs', 'slimscroll']);
    
    $("#upgrade-tier-btn").on("click", function(){ 
        var $email = $("#upgrade-tier-email").val();
        var $tier = $("#upgrade-tier").val();
        
        $.post('/admin/upgrade', { email: $email, tier: $tier }, function (data) {
            if (data.success === true) {
               alert('Successfully updated!');
            } else {
               alert('Updating failed! Ask Lloyd for help.');
            }
        },"json");
        
    });
    
});
</script>