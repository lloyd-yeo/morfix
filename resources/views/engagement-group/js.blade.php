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
    var $media_id = 0;
    $(".engagement-btn").on("click", function(){ 
        $media_id = $(this).attr('data-image-id');
        $profile_id = $(this).attr('data-profile-id');
        
        swal({
            title: 'Send for Engagement Group',
            text: "You will use 1 engagement credit for boosting this post.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, boost my post!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
              return new Promise(function (resolve, reject) {
                  $.post('/engagement-group/schedule/' + $media_id, { profile_id: $profile_id, comment: 1 }, function (data) {
                        if (data.success === true) {
                           resolve()
                        } else {
                           reject(data.message)
                        }
                    },"json");
              });
            },
            allowOutsideClick: false
          }).then(function () {
            swal({
              type: 'success',
              title: 'Sent!',
              text: 'Your picture will see increased engagement within the next 24 hours!'
            });
        });
        
    });

    $(".engagement-btn-no-comment").on("click", function(){
        $media_id = $(this).attr('data-image-id');
        $profile_id = $(this).attr('data-profile-id');

        swal({
            title: 'Send for Engagement Group',
            text: "You will use 1 engagement credit for boosting this post.",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, boost my post without Comments!',
            showLoaderOnConfirm: true,
            preConfirm: function () {
              return new Promise(function (resolve, reject) {
                  $.post('/engagement-group/schedule/' + $media_id, { profile_id: $profile_id, comment: 0 }, function (data) {
                        if (data.success === true) {
                           resolve()
                        } else {
                           reject(data.message)
                        }
                    },"json");
              });
            },
            allowOutsideClick: false
          }).then(function () {
            swal({
              type: 'success',
              title: 'Sent!',
              text: 'Your picture will see increased engagement within the next 24 hours!'
            });
        });

    });
    
    
    
//    $(".engagement-btn").on("click", function(){ 
//        swal({
//        title: 'Send for Engagement Group',
//        text: "You will use 1 engagement credit for boosting this post.",
//        type: 'info',
//        showCancelButton: true,
//        confirmButtonColor: '#3085d6',
//        cancelButtonColor: '#d33',
//        confirmButtonText: 'Yes, boost my post!'
//      }).then(function () {
//        swal(
//          'Sent!',
//          'Your picture will see increased engagement within the next 24 hours!',
//          'success'
//        );
//      });
//    });
});
</script>