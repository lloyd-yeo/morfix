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

$("#delete-all-pending-btn").on("click", function() {
    var $insta_id = $(this).attr('data-insta-id');
    swal({
        title: 'Delete All Pending Job',
        text: "Are you sure you wish to delete all pending jobs?",
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
                url: "/dm/logs/clear/" + $insta_id,
                dataType: "json",
                data: {

                },
                success: function (data) {
                    if (data.success === true) {
                        window.location.reload();
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
          text: 'We have removed this job from your pending DMs.'
        });
    });
});

$(".btn-cancel-job").on("click", function(){
    var $job_id = $(this).attr("data-job-id");
    
    swal({
        title: 'Delete Job',
        text: "Are you sure you wish to cancel this job? \n\
            By cancelling, Morfix will not DM this recipient anymore.",
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
                url: "/dm/logs/cancel/" + $job_id,
                dataType: "json",
                data: {

                },
                success: function (data) {
                    if (data.success === true) {
                        $("#dm-" + $job_id).remove();
                        resolve()
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
          text: 'We have removed this job from your pending DMs.'
        });
    });
});
</script>