<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
jQuery(function () {
    @if (Auth::user()->tier == 1)
    jQuery('#upgrade-modal').modal('show');
    @endif
});
</script>
