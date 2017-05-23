<script src="https://cdn.jsdelivr.net/emojione/2.2.7/lib/js/emojione.min.js"></script>
<script src="{{ asset('assets/js/plugins/jquery-textautocomplete/jquery.textcomplete.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dm_templates.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery(function () {
    initCommentsEmojiAutocomplete();
    
    @if (Auth::user()->tier == 1) 
    App.blocks('follow-up-block', 'state_loading');
    @endif
});
</script>