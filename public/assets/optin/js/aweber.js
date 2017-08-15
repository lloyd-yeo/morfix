var $ = jQuery.noConflict();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".btn-aweber").on("click", function(){
    $(this).html("<i class=\"icon-mail\"></i> Authorizing...");
    $("#aweber-token-div").show();
});

$(".btn-authorize").on("click", function(){
    var $code = jQuery('#aweber-verification-token').val();
    $.ajax({
            async: false,
            type: "POST",
            url: "aweber/verify/" + $code,
            dataType: "json",
            success: function (data) {
                if (data.success === true) {
                    $(".btn-aweber").html("<i class=\"icon-mail\"></i> AWeber has been authorized");
                    $("#aweber-token-div").fadeIn();
                } else {
                    alert(data.response);
                }
            }
    });
});

$(".btn-save-list").on("click", function(){
    var $listName = jQuery('#aweber-list-name').val();
    $.ajax({
            async: false,
            type: "POST",
            url: "aweber/list/add",
            dataType: "json",
            data: {
                'list_name': $listName
            },
            success: function (data) {
                if (data.success === true) {
                    alert(data.response);
//                    $(".btn-aweber").html("<i class=\"icon-mail\"></i> AWeber has been authorized");
//                    $("#aweber-token-div").fadeIn();
                } else {
                    alert(data.response);
                }
            }
    });
});


$(".btn-save-link").on("click", function(){
    var $referralLink = jQuery('#referral-link').val();
    $.ajax({
            async: false,
            type: "POST",
            url: "referral/link/add",
            dataType: "json",
            data: {
                'link': $referralLink
            },
            success: function (data) {
                if (data.success === true) {
                    alert(data.response);
                } else {
                    alert(data.response);
                }
            }
    });
    
});

$("#lead-btn").on("click", function(){ 
    var $email = jQuery('#lead-email').val();
    var $name = jQuery('#lead-name').val();
    
    $.ajax({
            type: "POST",
            url: "aweber/subscriber/add",
            dataType: "json",
            data: {
                'lead_email': $email,
                'lead_name': $name
            },
            success: function (data) {
                if (data.success === true) {
                    //alert(data.response);
                } else {
                    //alert(data.response);
                }
            }
    });
});