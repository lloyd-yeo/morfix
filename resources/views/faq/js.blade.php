<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    
    $(".question-link").on("click", function() { 
        $question_id = $(this).attr("data-q");
        jQuery('#question-modal').modal('show');
    });
    
});
</script>