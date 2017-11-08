@include('competition.css')
@extends('layouts.app')
@section('sidebar')
@include('sidebar', ['page' => 'competition'])
@endsection

@section('content')
<main id="main-container" style="padding-top: 10px;">
	<div class="bg-video" data-vide-bg="assets/img/videos/hero_tech" data-vide-options="posterType: jpg, position: 50% 75%" style="position: relative;">
    <div style="position: absolute; z-index: -1; top: 0px; left: 0px; bottom: 0px; right: 0px; overflow: hidden; background-size: cover; 
         background-color: transparent; background-repeat: no-repeat; background-position: 50% 75%; background-image: none;">
        <video autoplay="" loop="" muted="" style="margin: auto; position: absolute; z-index: -1; top: 75%; left: 50%; transform: translate(-50%, -75%); 
               visibility: visible; opacity: 1; width: 100%; height: auto;">
            <source src="assets/img/videos/hero_tech.mp4" type="video/mp4"><source src="assets/img/videos/hero_tech.webm" type="video/webm">
            <source src="assets/img/videos/hero_tech.ogv" type="video/ogg"></video></div>
    <div>
       <div class="row countdown text-center">
	    		<span class="date">
	    			Duration: {{$month}} {{$start_date}} - {{$end_date}}, {{$year}}
	    		</span>
	    		<span class="timer">12:00:00</span>
	    </div>
    </div>
		</div>
	<div class="content">
	    <div class="row statistics">
	      <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">DAILY REFERRAL</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Date
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="Commissions that will be paid out the 25th of this month" type="button"></i>
                </small>
            </div>
            <span class="h2 text-moneygreen animated flipInX" >5</span>
        </div>
        <div class="col-xs-6 col-sm-2 col-lg-2">
            <div class="font-w700 text-gray-darker animated fadeIn">TOTAL REFERRAL</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-calendar"></i> Start Date - End Date
                    <i class="fa fa-question-circle" style="margin-left: 5px;" data-toggle="tooltip" 
                       data-placement="top" title="Commissions that will be paid out the 25th of this month" type="button"></i>
                </small>
            </div>
            <span class="h2 text-moneygreen animated flipInX" >10</span>
        </div>
        <div class="col-xs-6 col-sm-6 col-lg-4">
            <div class="font-w700 text-gray-darker animated fadeIn">MY COMPETITION RANKING</div>
            <div class="text-muted animated fadeIn"><small><i class="si si-trophy"></i> All Time</small></div>
            <span class="h2 font-w300 text-modern animated flipInX">RANK #</span>
        </div>
	    </div>
	    <div class="row">
	    	<div class="col-lg-8 announcements">
	    		<!-- News -->
                <div class="block">
                    <div class="block-header">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title"> Announcements</h3>
                    </div>
                    <div class="block-content">
                        <ul class="list list-timeline pull-t">
                        </ul>
                    </div>
                </div>
                <!-- END News -->
	    	</div>
	    	<div class="col-lg-4 ranking">
	        	<div class="block">
	        				<div class="block-header">
	                    <h3 class="block-title"><i class="fa fa-trophy"></i> RANKING</h3>
	                </div>
	        	</div>
	      </div>
	    </div>
	    <div class="row">
	    	 <div class="referral">
	            <div class="block">
	                <div id="follower-chart-header" class="block-header">
	                    <h3 class="block-title"><i class="fa fa-instagram"></i> YOUR REFERRAL CHART</h3>
	                </div>

	                <div class="block-content block-content-full bg-gray-lighter text-center chart-container">
	                    <!--Chart.js Charts (initialized in js/pages/base_pages_dashboard.js), for more examples you can check out http://www.chartjs.org/docs/--> 
	                    <div id="follower-count-chart-" style="height: 374px;" >
	                        <canvas class="follower-count-chart-lines" data-csv="" data-label=""></canvas>
	                    </div>
	                </div>
	            </div>
	        </div>   
	    </div>
	    <div class="row">
	    	<div  class="block-header">
	          <h3 class="block-title"><i class="fa fa-address-card"></i> LIST OF REFERRALS</h3>
	      </div>
	    	<table class="table table-striped">
	    		<thead class="bg-primary">
	    			<td>User's Information</td>
	    			<td>Email Address</td>
	    			<td>Tier</td>
	    		</thead>
	    		<tbody>
	    			<tr>
	    				<td>Sample</td>
	    				<td>Sample@gmail.com</td>
	    				<td>1</td>
	    			</tr>
	    		</tbody>
	    	</table>
	    </div>
	    <div class="row">
	    	<div class="resource">
	    		<div  class="block-header">
	          <h3 class="block-title"><i class="fa fa-bars"></i> RESOURCES</h3>
	      	</div>
	    	</div>
	    </div>
	    <div class="row footer">
	   		<span class="pull-right">Terms and Conditions</span>
	    </div>
	</div>
</main>
@endsection

@section('js')
@include('dashboard.js')
@endsection
