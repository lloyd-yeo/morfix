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
<script src="{{ asset('assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datetimepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    $("#engagement-btn".on("click", function(){ 
        swal({
        title: 'Send for Engagement Group',
        text: "You will use <b>1</b> engagement credit for boosting this post.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, boot my post!'
      }).then(function () {
        swal(
          'Sent!',
          'Your picture will see increased engagement within the next 24 hours!',
          'success'
        );
      });
      
    }));
});
</script>