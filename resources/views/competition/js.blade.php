<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

<script>
jQuery(function () {
	var x = setInterval(function (){
		$.get("/competition/timer", function(timer){
        $(".timer").html(timer);
    });
	}, 1000);
});
</script>