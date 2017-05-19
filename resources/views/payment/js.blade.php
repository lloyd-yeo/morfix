<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var key = "pk_live_WrvnbbOwMxU7FwZzaoTdaUpa";
var handler = StripeCheckout.configure({
    key: key,
    image: 'https://morfix.co/app/assets/img/mx-black-crop.png',
    locale: 'auto',
    token: function (token) {
        // You can access the token ID with `token.id`.
        // Get the token ID to your server-side code for use.
        $.post("cron-subscribe-user-premium.php", { stripeToken: token.id, email: "{{ Auth::user()->email }}" },
            function (data) {
                $("#loading-div").hide();
                if (data.success === true) {
                    alert("You have upgraded to the Premium package. Enjoy the full suite of MorfiX's function!");
                    window.location.href="payment.php";
                } else {
                    alert(data.msg);
                }
            },"json");
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