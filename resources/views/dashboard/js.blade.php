<!-- Page Plugins -->
<script src="{{ asset('assets/js/plugins/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/base_pages_dashboard.js') }}"></script>
<script src="{{ asset('assets/js/pages/base_ui_activity.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery(function () {
    // Init page helpers (Slick Slider plugin, Appear, CountTo)
    App.initHelpers(['slick', 'appear', 'appear-countTo']);

    // this is the id of the form
    $("#add-instagram-profile-form").submit(function (e) {

        var url = "instagram-profile/add"; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: $("#add-instagram-profile-form").serialize(), // serializes the form's elements.
            success: function (data) {
                if (data.success === true) {
                    swal('Success', 'Profile successfully added!', 'success');
                } else {
                    swal('Oops...', data.response, 'error');
                }
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.

    });
});

$(".remove-profile-btn").on("click");
</script>