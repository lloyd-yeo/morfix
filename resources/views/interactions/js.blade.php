<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery(function () {
    // Init page helpers (Select2 + Tags Inputs plugins)
    App.initHelpers(['select2', 'tags-inputs']);
});

$(".toggle-like-btn").on("click", function () {
    var url = "like/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-comment-btn").on("click", function () {
    var url = "comment/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-follow-btn").on("click", function () {
    var url = "follow/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-unfollow-btn").on("click", function () {
    var url = "unfollow/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-niche").on('change', function () {
    var url = "niche/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    var optionSelected = $("option:selected", this);
    var optionSelectedText = optionSelected.text();
    var valueSelected = this.value;
    toggleNiche(url, valueSelected, optionSelectedText);
});



function toggleInteraction(url) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            action: "toggle"
        },
        success: function (data) {
            if (data.success === true) {
                jQuery.notify({
                    icon: "fa fa-check",
                    message: data.message,
                    url: ''
                },
                        {
                            element: 'body',
                            type: 'success',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'center'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
            } else {
                jQuery.notify({
                    icon: "fa fa-times",
                    message: data.message,
                    url: ''
                },
                        {
                            element: 'body',
                            type: 'danger',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
            }
        }
    });
}
;

function toggleNiche(url, nicheId, nicheName) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            niche: nicheId
        },
        success: function (data) {
            if (data.success === true) {
                jQuery.notify({
                    icon: "fa fa-check",
                    message: "You have updated your niche to: <b>" + nicheName + "</b>",
                    url: ''
                },
                        {
                            element: 'body',
                            type: 'success',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'center'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
            } else {
                jQuery.notify({
                    icon: "fa fa-times",
                    message: data.message,
                    url: ''
                },
                        {
                            element: 'body',
                            type: 'danger',
                            allow_dismiss: true,
                            newest_on_top: true,
                            showProgressbar: false,
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            offset: 20,
                            spacing: 10,
                            z_index: 1033,
                            delay: 5000,
                            timer: 1000,
                            animate: {
                                enter: 'animated fadeIn',
                                exit: 'animated fadeOutDown'
                            }
                        });
            }
        }
    });
};

jQuery('.js-validation-material').validate({
    ignore: [],
    errorClass: 'help-block text-right animated fadeInDown',
    errorElement: 'div',
    errorPlacement: function (error, e) {
        jQuery(e).parents('.form-group > div').append(error);
    },
    highlight: function (e) {
        var elem = jQuery(e);

        elem.closest('.form-group').removeClass('has-error').addClass('has-error');
        elem.closest('.help-block').remove();
    },
    success: function (e) {
        var elem = jQuery(e);

        elem.closest('.form-group').removeClass('has-error');
        elem.closest('.help-block').remove();
    },
    rules: {
        'val-username2': {
            required: true,
            minlength: 3
        },
        'val-email2': {
            required: true,
            email: true
        },
        'val-password2': {
            required: true,
            minlength: 5
        },
        'val-confirm-password2': {
            required: true,
            equalTo: '#val-password2'
        },
        'val-select22': {
            required: true
        },
        'val-select2-multiple2': {
            required: true,
            minlength: 2
        },
        'val-suggestions2': {
            required: true,
            minlength: 5
        },
        'val-skill2': {
            required: true
        },
        'val-currency2': {
            required: true,
            currency: ['$', true]
        },
        'val-website2': {
            required: true,
            url: true
        },
        'val-phoneus2': {
            required: true,
            phoneUS: true
        },
        'val-digits2': {
            required: true,
            digits: true
        },
        'val-number2': {
            required: true,
            number: true
        },
        'val-range2': {
            required: true,
            range: [1, 5]
        },
        'val-terms2': {
            required: true
        }
    },
    messages: {
        'val-username2': {
            required: 'Please enter a username',
            minlength: 'Your username must consist of at least 3 characters'
        },
        'val-email2': 'Please enter a valid email address',
        'val-password2': {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long'
        },
        'val-confirm-password2': {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long',
            equalTo: 'Please enter the same password as above'
        },
        'val-select22': 'Please select a value!',
        'val-select2-multiple2': 'Please select at least 2 values!',
        'val-suggestions2': 'What can we do to become better?',
        'val-skill2': 'Please select a skill!',
        'val-currency2': 'Please enter a price!',
        'val-website2': 'Please enter your website!',
        'val-phoneus2': 'Please enter a US phone!',
        'val-digits2': 'Please enter only digits!',
        'val-number2': 'Please enter a number!',
        'val-range2': 'Please enter a number between 1 and 5!',
        'val-terms2': 'You must agree to the service terms!'
    }
});

</script>