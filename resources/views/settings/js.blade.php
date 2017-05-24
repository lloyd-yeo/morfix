<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/base_tables_datatables.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    
});

$(".btn-cancel-subscription").on("click", function(){ 
    var $sub_id = $(this).attr("data-sub-id");
    
    
    swal({
        title: 'Cancel Subscription',
        text: "Are you sure you wish to cancel your subscription? \n\
            By cancelling, you will lose the functionality associated with this package & forfeit any commissions that you have earned.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          return new Promise(function (resolve, reject) {
              $.ajax({
                async: false,
                type: "POST",
                url: "/settings/subscription/cancel/" + $sub_id,
                dataType: "json",
                data: {

                },
                success: function (data) {
                    resolve()
                    if (data.success === true) {
                        
                    } else {

                    }
                }
            });
          });
        },
        allowOutsideClick: false
      }).then(function () {
        swal({
          type: 'success',
          title: 'Cancelled!',
          text: 'We have cancelled your subscription.'
        });
    });
});
</script>