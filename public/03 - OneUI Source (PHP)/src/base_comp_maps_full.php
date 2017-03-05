<?php require 'inc/config.php'; ?>
<?php
// Specific Page Options
$one->l_sidebar_mini = true;
?>
<?php require 'inc/views/template_head_start.php'; ?>
<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/base_head.php'; ?>

<!-- Google Map Container is initialized in js/pages/base_comp_maps_full.js, for more examples you can check out https://hpneo.github.io/gmaps/ -->
<div id="js-map-full"></div>

<?php require 'inc/views/base_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<!-- Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps) -->
<!-- For more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key -->
<script src="//maps.googleapis.com/maps/api/js?key=<?php echo $one->google_maps_api_key; ?>"></script>
<script src="<?php echo $one->assets_folder; ?>/js/plugins/gmapsjs/gmaps.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $one->assets_folder; ?>/js/pages/base_comp_maps_full.js"></script>

<?php require 'inc/views/template_footer_end.php'; ?>