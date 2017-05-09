<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function () {
    // Init page helpers (Appear, CountTo)
    App.initHelpers(['appear', 'appear-countTo']);
});
</script>