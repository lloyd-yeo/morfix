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
    
    var $scheduleId;
    
    $(".btn-cancel-schedule").on("click", function(){ 
        
        $scheduleId = $(this).attr("data-schedule-id");
        
        $.ajax({
            type: "POST",
            url: '/post-scheduling/delete',
            dataType: "json",
            data: {
                schedule_id: $scheduleId
            },
            success: function (data) {
                if (data.success === true) {
                    swal('Success', data.response, 'success');
                    $("#schedule-" + $scheduleId).remove();
                } else {
                    swal('Oops...', data.response, 'fail');
                }
            }
        });
        
    });
    
});
</script>