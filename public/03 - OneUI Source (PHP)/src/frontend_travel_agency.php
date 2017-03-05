<?php require 'inc/config.php'; require 'inc/frontend_config.php'; ?>
<?php require 'inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css">

<?php require 'inc/views/template_head_end.php'; ?>
<?php require 'inc/views/frontend_head.php'; ?>

<!-- Hero Content -->
<div class="bg-image" style="background-image: url('<?php echo $one->assets_folder; ?>/img/photos/photo28@2x.jpg');">
    <!-- Search Content -->
    <section class="content content-full content-boxed overflow-hidden">
        <!-- Bootstrap Datepicker (.input-daterange class is initialized in App() -> uiHelperDatepicker()) -->
        <!-- For more info and examples you can check out https://github.com/eternicode/bootstrap-datepicker -->
        <div class="push-100-t push-100">
            <h1 class="font-s48 font-w700 text-uppercase text-white push-10 visibility-hidden text-center" data-toggle="appear" data-class="animated fadeInDown">Travel The World</h1>
            <h2 class="h3 font-w400 text-white-op push-50 visibility-hidden text-center" data-toggle="appear" data-timeout="750">Let us help you explore the world, one step at a time.</h2>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="block">
                        <ul class="nav nav-tabs nav-justified" data-toggle="tabs">
                            <li class="active">
                                <a href="#travel-flights"><i class="fa fa-plane text-primary push-5-r"></i> <span class="text-primary-dark">Flights</span></a>
                            </li>
                            <li>
                                <a href="#travel-hotels"><i class="fa fa-hotel text-success push-5-r"></i> <span class="text-primary-dark">Packages</span></a>
                            </li>
                            <li>
                                <a href="#travel-packages"><i class="fa fa-gift text-danger push-5-r"></i> <span class="text-primary-dark">Packages</span></a>
                            </li>
                        </ul>
                        <div class="block-content tab-content mheight-200">
                            <div class="tab-pane active" id="travel-flights">
                                <form class="form-horizontal" action="frontend_travel_home.php" method="post" onsubmit="return false;">
                                    <div class="form-group items-push push-10">
                                        <div class="col-sm-6">
                                            <label for="travel-flights-from">FROM</label>
                                            <input class="form-control" type="text" id="travel-flights-from" name="travel-flights-from" placeholder="Eg. Paris, FR">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="travel-flights-to">TO</label>
                                            <input class="form-control" type="text" id="travel-flights-to" name="travel-flights-to" placeholder="Eg. New York, US">
                                        </div>
                                        <div class="col-sm-10">
                                            <label>WHEN?</label>
                                            <div class="input-daterange input-group" data-date-format="mm/dd/yyyy">
                                                <input class="form-control" type="text" id="travel-flights-departure" name="travel-flights-departure" placeholder="Departure">
                                                <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                                <input class="form-control" type="text" id="travel-flights-return" name="travel-flights-return" placeholder="Return">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="travel-flights-adults">ADULTS</label>
                                            <input class="form-control" type="number" min="1" max="10" id="travel-flights-adults" name="travel-flights-adults" value="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-noborder btn-rounded btn-primary text-uppercase" type="submit"><i class="fa fa-search push-5-r"></i> Search Flights</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="travel-hotels">
                                <form class="form-horizontal" action="frontend_travel_home.php" method="post" onsubmit="return false;">
                                    <div class="form-group items-push push-10">
                                        <div class="col-xs-12">
                                            <label for="travel-hotels-where">WHERE</label>
                                            <input class="form-control" type="text" id="travel-hotels-where" name="travel-hotels-where" placeholder="Eg. Paris, FR">
                                        </div>
                                        <div class="col-sm-10">
                                            <label>WHEN?</label>
                                            <div class="input-daterange input-group" data-date-format="mm/dd/yyyy">
                                                <input class="form-control" type="text" id="travel-hotels-arrival" name="travel-hotels-arrival" placeholder="Arrival">
                                                <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                                <input class="form-control" type="text" id="travel-hotels-departure" name="travel-hotels-departure" placeholder="Departure">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="travel-hotels-adults">ADULTS</label>
                                            <input class="form-control" type="number" min="1" max="10" id="travel-hotels-adults" name="travel-hotels-adults" value="2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-noborder btn-rounded btn-success text-uppercase" type="submit"><i class="fa fa-search push-5-r"></i> Search Hotels</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="travel-packages">
                                <form class="form-horizontal" action="frontend_travel_home.php" method="post" onsubmit="return false;">
                                    <div class="form-group items-push push-10">
                                        <div class="col-xs-12">
                                            <label for="travel-packages-destination">DESTINATION</label>
                                            <input class="form-control" type="text" id="travel-packages-destination" name="travel-packages-destination" placeholder="Eg. London, GB">
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="travel-packages-month">MONTH</label>
                                            <select class="form-control" id="travel-packages-month" name="travel-packages-month">
                                                <option value="0">When?</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="travel-packages-budget">BUDGET</label>
                                            <select class="form-control" id="travel-packages-budget" name="travel-packages-budget">
                                                <option value="0">How much?</option>
                                                <option value="1">$99 to $499</option>
                                                <option value="2">$500 to $999</option>
                                                <option value="3">$1000 to $1999</option>
                                                <option value="4">$2000 to $2999</option>
                                                <option value="5">$3000 to $4999</option>
                                                <option value="6">$5000 to $9999</option>
                                                <option value="6">&gt; $9999</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="travel-packages-adults">ADULTS</label>
                                            <input class="form-control" type="number" min="1" max="10" id="travel-packages-adults" name="travel-packages-adults" value="2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-noborder btn-rounded btn-danger text-uppercase" type="submit"><i class="fa fa-search push-5-r"></i> Search Packages</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Search Content -->

    <!-- Features -->
    <div class="bg-black-op">
        <section class="content content-boxed">
            <div class="push-20-t push-50">
                <div class="row items-push text-center">
                    <div class="col-xs-6 col-md-3 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated flipInY" data-timeout="200">
                        <div class="item item-2x item-circle push-10">
                            <i class="si si-plane text-white"></i>
                        </div>
                        <div class="font-w600 text-white-op text-uppercase">Cheap Flights</div>
                    </div>
                    <div class="col-xs-6 col-md-3 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated flipInY" data-timeout="400">
                        <div class="item item-2x item-circle push-10">
                            <i class="si si-heart text-white"></i>
                        </div>
                        <div class="font-w600 text-white-op text-uppercase">Best Deals</div>
                    </div>
                    <div class="col-xs-6 col-md-3 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated flipInY" data-timeout="600">
                        <div class="item item-2x item-circle push-10">
                            <i class="si si-clock text-white"></i>
                        </div>
                        <div class="font-w600 text-white-op text-uppercase">Save Time</div>
                    </div>
                    <div class="col-xs-6 col-md-3 visibility-hidden" data-toggle="appear" data-offset="-100" data-class="animated flipInY" data-timeout="800">
                        <div class="item item-2x item-circle push-10">
                            <i class="si si-support text-white"></i>
                        </div>
                        <div class="font-w600 text-white-op text-uppercase">24/7 Support</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- END Features -->
</div>
<!-- END Hero Content -->

<!-- Packages -->
<div class="bg-white">
    <section class="content content-full content-boxed">
        <!-- Section Content -->
        <div class="text-center push-50-t push-50">
            <div>Where would you like to go today?</div>
            <h2 class="h1 text-black">The best packages this week.</h2>
        </div>
        <div class="row push-50">
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo29.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">New York</h2>
                        <p class="text-white-op">7 Nights | From $1299</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo30.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Norway</h2>
                        <p class="text-white-op">4 Nights | From $999</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo31.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Hawaii</h2>
                        <p class="text-white-op">9 Nights | From $2399</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo32.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Egypt</h2>
                        <p class="text-white-op">3 Nights | From $599</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo33.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">San Francisco</h2>
                        <p class="text-white-op">6 Nights | From $1699</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo34.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Japan</h2>
                        <p class="text-white-op">8 Nights | From $1999</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo35.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Paris</h2>
                        <p class="text-white-op">7 Nights | From $1399</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo21.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Greece</h2>
                        <p class="text-white-op">7 Nights | From $899</p>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a class="block block-themed bg-image" style="background-image: url('assets/img/photos/photo36.jpg');" href="frontend_travel_package.php">
                    <div class="block-header text-right push-150">
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                        <span class="text-white-op"><i class="fa fa-star"></i></span>
                    </div>
                    <div class="block-content bg-black-op">
                        <h2 class="font-w700 h3 push-10 text-white text-uppercase">Space</h2>
                        <p class="text-white-op">3 Days | From $25499</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- END Section Content -->
    </section>
</div>
<!-- END Packages -->

<!-- Get in touch -->
<div class="bg-gray-lighter">
    <section class="content content-full content-boxed">
        <!-- Section Content -->
        <div class="push-20-t push-20 text-center">
            <h3 class="h4 push-20 visibility-hidden" data-toggle="appear">Can’t find a package for you? Don’t worry!</h3>
            <a class="btn btn-rounded btn-noborder btn-lg btn-success visibility-hidden" data-toggle="appear" data-class="animated bounceIn" href="frontend_contact.php"><i class="fa fa-envelope-o push-5-r"></i> Get in touch</a>
        </div>
        <!-- END Section Content -->
    </section>
</div>
<!-- END Get in touch -->

<?php require 'inc/views/frontend_footer.php'; ?>
<?php require 'inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $one->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

<!-- Page JS Code -->
<script>
    jQuery(function(){
        // Init page helpers (Appear + BS Datepicker plugins)
        App.initHelpers(['appear', 'datepicker']);
    });
</script>

<?php require 'inc/views/template_footer_end.php'; ?>