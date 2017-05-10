<!-- Page Plugins -->
<script src="{{ asset('assets/js/plugins/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/base_pages_dashboard.js') }}"></script>
<script src="{{ asset('assets/js/pages/base_ui_activity.js') }}"></script>

<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/base_forms_wizard.js') }}"></script>
<script src="{{ asset('assets/js/plugins/slick/slick.min.js') }}"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    // Init page helpers (Slick Slider plugin, Appear, CountTo)
    App.initHelpers(['slick', 'appear', 'appear-countTo']);
    
    $(".btn-retry").on("click", function(){
        var $igUsername = jQuery('#validation-ig-username').val();
        var $igPassword = jQuery('#validation-ig-password').val();
        
        $.ajax({
                async: false,
                type: "POST",
                url: "profile/ig/add",
                dataType: "json",
                data: {
                    'ig-username': $igUsername,
                    'ig-password': $igPassword
                },
                success: function (data) {
                    if (data.success === true) {
                        swal('Success', data.response, 'success');
                        jQuery('#modal-addprofile').modal('hide');
                    } else {
                        if (data.type === 'ig_added') {
                            swal('Oops...', data.response, 'error');
                        } else if (data.type === 'checkpoint') {
                            swal('Oops...', data.response, 'error');
                        } else {
                           swal('Oops...', data.response, 'error');
                        }
                    }
                }
            });
        
    });
});

$(".remove-profile-btn").on("click");
</script>