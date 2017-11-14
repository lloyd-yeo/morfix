<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
{{--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>--}}
{{--<script src="https://checkout.stripe.com/checkout.js"></script>--}}

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var key = "pk_live_WrvnbbOwMxU7FwZzaoTdaUpa";

{{--var premium_handler = StripeCheckout.configure({--}}
    {{--key: key,--}}
    {{--image: 'https://morfix.co/app/assets/img/mx-black-crop.png',--}}
    {{--locale: 'auto',--}}
    {{--token: function (token) {--}}
        {{--App.loader('show');--}}
        {{----}}
        {{--// You can access the token ID with `token.id`.--}}
        {{--// Get the token ID to your server-side code for use.--}}
        {{--$.post("/upgrade/premium", { stripeToken: token.id, email: "{{ Auth::user()->email }}" },--}}
            {{--function (data) {--}}
                {{--if (data.success === true) {--}}
                    {{--swal('Congratulations!', "You have successfully been upgraded to Premium!", 'success');--}}
                {{--} else {--}}
                    {{--swal('Oops...', data.message, 'error');--}}
                {{--}--}}
            {{--},"json").always( function(){ App.loader('hide'); } );--}}
            {{----}}
    {{--}--}}
{{--});--}}

{{--var pro_handler = StripeCheckout.configure({--}}
    {{--key: key,--}}
    {{--image: 'https://morfix.co/app/assets/img/mx-black-crop.png',--}}
    {{--locale: 'auto',--}}
    {{--token: function (token) {--}}
        {{--// You can access the token ID with `token.id`.--}}
        {{--// Get the token ID to your server-side code for use.--}}
        {{--$.post("/upgrade/pro", { stripeToken: token.id, email: "{{ Auth::user()->email }}" },--}}
            {{--function (data) {--}}
                {{--if (data.success === true) {--}}
                    {{--swal('Congratulations!', "You have successfully been upgraded to Pro!", 'success');--}}
                {{--} else {--}}
                    {{--swal('Oops...', data.message, 'error');--}}
                {{--}--}}
            {{--},"json");--}}
    {{--}--}}
{{--});--}}

{{--var business_handler = StripeCheckout.configure({--}}
    {{--key: key,--}}
    {{--image: 'https://morfix.co/app/assets/img/mx-black-crop.png',--}}
    {{--locale: 'auto',--}}
    {{--token: function (token) {--}}
        {{--// You can access the token ID with `token.id`.--}}
        {{--// Get the token ID to your server-side code for use.--}}
        {{--$.post("/upgrade/business", { stripeToken: token.id, email: "{{ Auth::user()->email }}" },--}}
            {{--function (data) {--}}
                {{--if (data.success === true) {--}}
                    {{--swal('Congratulations!', "You have successfully been upgraded to Business!", 'success');--}}
                {{--} else {--}}
                    {{--swal('Oops...', data.message, 'error');--}}
                {{--}--}}
            {{--},"json");--}}
    {{--}--}}
{{--});--}}

{{--var mastermind_handler = StripeCheckout.configure({--}}
    {{--key: key,--}}
    {{--image: 'https://morfix.co/app/assets/img/mx-black-crop.png',--}}
    {{--locale: 'auto',--}}
    {{--token: function (token) {--}}
        {{--// You can access the token ID with `token.id`.--}}
        {{--// Get the token ID to your server-side code for use.--}}
        {{--$.post("/upgrade/mastermind", { stripeToken: token.id, email: "{{ Auth::user()->email }}" },--}}
            {{--function (data) {--}}
                {{--if (data.success === true) {--}}
                    {{--swal('Congratulations!', "You have successfully been upgraded to Mastermind!", 'success');--}}
                {{--} else {--}}
                    {{--swal('Oops...', data.message, 'error');--}}
                {{--}--}}
            {{--},"json");--}}
    {{--}--}}
{{--});--}}


//$("#premium-btn").on("click", function(){
    // Open Checkout with further options:
//    premium_handler.open({
//        name: 'Morfix.co',
//        description: 'Premium Package ($37 per mth)',
//        panelLabel: 'Subscribe',
//        email: "{{ Auth::user()->email }}"
//    });
//});

//$("#pro-btn").on("click", function(){
    // Open Checkout with further options:
//    pro_handler.open({
//        name: 'Morfix.co',
//        description: 'Pro Package ($370 per yr)',
//        panelLabel: 'Subscribe',
//        email: "{{ Auth::user()->email }}"
//    });
//});

//$("#business-btn").on("click", function(){
    // Open Checkout with further options:
//    business_handler.open({
//        name: 'Morfix.co',
//        description: 'Business Package ($97 per mth)',
//        panelLabel: 'Subscribe',
//        email: "{{ Auth::user()->email }}"
//    });
//});

//$("#mastermind-btn").on("click", function(){
    // Open Checkout with further options:
//    pro_handler.open({
//        name: 'Morfix.co',
//        description: 'Mastermind Package ($970 per yr)',
//        panelLabel: 'Subscribe',
//        email: "{{ Auth::user()->email }}"
//    });
//});

jQuery(function () {
    @if (Auth::user()->tier == 1)
    jQuery('#upgrade-modal').modal('show');
    @endif
});
</script>
