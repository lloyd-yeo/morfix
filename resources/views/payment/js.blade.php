<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$("#premium-btn").on("click", function(){ 
    // Open Checkout with further options:
    handler.open({
        name: 'Morfix.co',
        description: 'Premium Package ($37 per mth)',
        panelLabel: 'Subscribe',
        email: "{{ Auth::user()->email }}"
    });
});
$("#pro-btn").on("click", function(){ });
$("#business-btn").on("click", function(){ });
$("#mastermind-btn").on("click", function(){ });
</script>