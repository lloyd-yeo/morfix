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
    
    Dropzone.autoDiscover = false;
    
    Dropzone.options.imageUpload = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 5, // MB
        parallelUploads: 2, //limits number of files processed to reduce stress on server
        addRemoveLinks: true,
        accept: function(file, done) {
            // TODO: Image upload validation
            done();
        },
        sending: function(file, xhr, formData) {
            // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
            formData.append("_token", $('meta[name="csrf-token"]').attr('content')); // Laravel expect the token post value to be named _token by default
        },
        init: function() {
            this.on("success", function(file, response) {
                // On successful upload do whatever :-)
                console.log(response);
            });
        }
    };

    // Manually init dropzone on our element.
    var myDropzone = new Dropzone("#image-upload", {
        url: '/post-scheduling/add'
    });
    
    // Init page helpers (Select2 + Tags Inputs plugins)
    App.initHelpers(['datepicker', 'datetimepicker', 'colorpicker', 'maxlength', 'select2', 'tags-inputs', 'slimscroll', 'magnific-popup']);
    
    initCommentsEmojiAutocomplete();
    
    initValidationMaterial();
    
    var $imgId = 0;
    var $profileId = 0;
    
    $(".upload-photo").on("click", function(){
        $imgId = $(this).attr("data-image-id");
        $profileId = $(this).attr("data-profile-id");
        jQuery('#modalScheduleImage').modal('show');
    });
    
    $(".upload-default-photo").on("click", function(){ 
        $imgId = $(this).attr("data-image-id");
        $profileId = $(this).attr("data-profile-id");
        jQuery('#modalScheduleGalleryImage').modal('show');
    });
    
    $("#schedule-btn").on("click", function(){
        var $dateToPost = $("#schedule-date").val();
        var $caption = $("#image-caption-txt").val();
        var $firstComment = $("#first-comment-txt").val();
        $.ajax({
            type: "POST",
            url: '/post-scheduling/schedule/' + $profileId,
            dataType: "json",
            data: {
                img_id: $imgId,
                date_to_post: $dateToPost,
                caption: $caption,
                first_comment: $firstComment,
                img_source: 'user'
            },
            success: function (data) {
                if (data.success === true) {
                    swal('Success', data.response, 'success');
                }
            }
        });
        
        jQuery('#modalScheduleImage').modal('toggle');
    });
    
    $("#schedule-gallery-btn").on("click", function(){
        var $dateToPost = $("#gallery-schedule-date").val();
        var $caption = $("#gallery-image-caption-txt").val();
        var $firstComment = $("#gallery-first-comment-txt").val();
        
        $.ajax({
            type: "POST",
            url: '/post-scheduling/schedule/' + $profileId,
            dataType: "json",
            data: {
                img_id: $imgId,
                date_to_post: $dateToPost,
                caption: $caption,
                first_comment: $firstComment,
                img_source: 'gallery'
            },
            success: function (data) {
                if (data.success === true) {
                    swal('Success', data.response, 'success');
                }
            }
        });
        
        jQuery('#modalScheduleGalleryImage').modal('toggle');
    });
});
</script>