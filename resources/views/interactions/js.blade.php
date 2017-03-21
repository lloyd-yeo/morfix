<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
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

$(".toggle-like-btn").on("click", function() { 
    var url = "like/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-comment-btn").on("click", function() { 
    var url = "comment/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-follow-btn").on("click", function() { 
    var url = "follow/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-unfollow-btn").on("click", function() { 
    var url = "unfollow/"; // the script where you handle the form input.
    var profile_id = $(this).attr("data-id");
    url = url + profile_id;
    toggleInteraction(url);
});

$(".toggle-niche").on('change', function() {
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
};

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

</script>