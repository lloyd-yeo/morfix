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
    
    var $checkpointProfileId;
    var $incorrectPwProfileId;
    
    $(".checkpoint-btn").on("click", function() { 
        $checkpointProfileId = $(this).attr("data-profile-id");
        jQuery('#modal-checkpoint').modal('show');
    });
    
    $(".incorrect-pw-btn").on("click", function(){ 
        $incorrectPwProfileId = $(this).attr("data-profile-id");
        swal({
            title: 'Enter the correct password for this profile',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: function (password) {
               App.loader('show');
              return new Promise(function (resolve, reject) {
                $.ajax({
                    async: true,
                    type: "POST",
                    url: "profile/ig/changepassword",
                    dataType: "json",
                    data: {
                        'profile-id': $incorrectPwProfileId,
                        'password': password
                    },
                    success: function (data) {
                        if (data.success === true) {
                            localStorage.setItem("status", data.response);
                            location.reload(true);
                        } else {
                            reject('This password is incorrect.');
                        }
                    }
                });
              });
            },
            allowOutsideClick: false
          }).then(function (email) {
            swal({
              type: 'success',
              title: 'Password successfully changed!'
            });
          });
    });
    
    $(".btn-retry-checkpt").on("click", function(){ 
        App.loader('show');
        var $icon = jQuery('#retry-btn-spinner');
        $icon.addClass("fa-spin");
        $.ajax({
            async: true,
            type: "POST",
            url: "profile/ig/checkpoint",
            dataType: "json",
            data: {
                'profile-id': $checkpointProfileId
            },
            success: function (data) {
                if (data.success === true) {
                    localStorage.setItem("status", data.response);
                    location.reload(true);
                } else {
                    swal('Oops...', data.response, 'error');
                }

            }
        });
        App.loader('hide');
    });
    
    $(".btn-retry").on("click", function(){
        App.loader('show');
        
        var $igUsername = jQuery('#validation-ig-username').val();
        var $igPassword = jQuery('#validation-ig-password').val();
        var $icon = jQuery('#retry-btn-spinner');
        
        $icon.addClass("fa-spin");
        
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
                    console.log(data);

                    if (data.success === true) {
                        jQuery('#modal-addprofile').modal('hide');
                        localStorage.setItem("status", data.response);
                        location.reload(true);
                    } else {
                        console.log(data.type)
                        if (data.type == 'ig_added') {
                            swal('Oops...', data.response, 'error');
                        } else if (data.type == 'checkpoint') {
                            swal('Oops...', data.response, 'error');
                        } else if (data.type == 'challenge') {
                            swal('Oops...', data.response, 'error');
                            console.log(data.active_request);
                            $("#active-request").val(data.active_request);
//                            $("#challenge-url").attr("href", data.link);
//                            $("#challenge-url").html(data.link);
                        } else {
                           swal('Oops...', data.response, 'error');
                        }
                    }
                    App.loader('hide');
                }
        });
        
        $icon.removeClass("fa-spin");
    });
    
    $(".remove-profile-btn").on("click", function(){ 
        var $igId = $(this).attr("data-id");
        $.ajax({
                async: false,
                type: "POST",
                url: "profile/ig/remove/" + $igId,
                dataType: "json",
                data: {
                    
                },
                success: function (data) {
                    if (data.success === true) {
                        localStorage.setItem("status", data.response);
                        location.reload(true);
                    } else {
                        swal('Failed', data.response, 'fail');
                    }
                }
        });
    });

    if (localStorage.status) {
        swal({
            title: 'Success', 
            text: localStorage.status, 
            type: 'success'
        });
        localStorage.removeItem("status");
    }
    
    @if (Auth::user()->close_dashboard_tut == 0)
		var $hide_tutorial = 0;
		$("#tut-cbx").on("click", function(){
            $hide_tutorial = 1;
		});
	    $("#closetutorial-btn").on("click", function(){
			if ($hide_tutorial == 1) {
                $.post( "/dashboard/tutorial/hide" );
			}
	    });

        jQuery('#dashboard-tutorial-modal').modal('show');    
    @endif
});

$("#relogin-btn").on("click", function () {
    $active_request_id = $("#active-request").val();
    var jqxhr = $.post("/profile/request/retry",
        {
            active_request: $active_request_id,
        }
        , function (data) {
            if (data.success) {
                alert("Hang on while we re-try adding your profile. Please do not close this window.")
            } else {
            }
        });
});

setInterval(function(){
    $active_request_id = $("#active-request").val();

    var jqxhr = $.post("/profile/request/check",
        {
            active_request: $active_request_id,
        }
        , function (data) {
			if (data.success) {
			    if (data.working_on == 3) {
                    $("#waiting-message").hide();
                    $("#verify-message").show();
                    $("#challenge-url").html(data.challenge_url);
			    }
			    if (data.working_on == 4) {

                }
			} else {
                $("#waiting-message").show();
			}
        });
    }, 10000);

$(".remove-profile-btn").on("click");
</script>