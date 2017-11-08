@include('competition.css')
<link rel="stylesheet" media="screen" href="https://www.clickfunnel.com/assets/lander.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700%7COswald:400,700%7CDroid+Sans:400,700%7CRoboto:400,700%7CLato:400,700%7CPT+Sans:400,700%7CSource+Sans+Pro:400,700%7CNoto+Sans:400,700%7CPT+Sans:400,700%7CUbuntu:400,700%7CBitter:400,700%7CPT+Serif:400,700%7CRokkitt:400,700%7CDroid+Serif:400,700%7CRaleway:400,700%7CInconsolata:400,700" rel="stylesheet" type="text/css">
<div class="content">
    <div class="row">
        <div class="page-heading">
            		<button class="btn btn-default"><i class="fa fa-ellipsis-v"></i></button>
                <button class="btn btn-default" id="title"><i class="fa fa-trophy"></i> Competition <small> (Description here)</small></button>
        </div>
    </div>
    <div class="row countdown text-center">
    		<span class="date">
    			Duration: November 1 - 8, 2017
    		</span>
    		<span class="timer">12:00:00</span>
    </div>
    <div class="row">
      	<span class="col-lg-2">
      		<span class="title">NUMBER REFERRAL TODAY</span>
      		<span class="value">#</span>
      	</span>
      	<span class="col-lg-2">
      		<span class="title">TOTAL REFERRAL</span>
      		<span class="value">#</span>
      	</span>
      	<span class="col-lg-2">
      		<span class="title">MY LEADERBOARD RANKING</span>
      		<span class="value">#</span>
      	</span>
    </div>
    <div class="row">
    	<div class="col-lg-8 announcements">
    		<div class="block">
        				<div class="block-header">
                    <h3 class="block-title"><i class="fa fa-bell"></i> ANNOUNCEMENTS</h3>
                </div>
        </div>
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
    		<a href="#">Files Here</a>
    	</div>
    	<div class="terms">
    		<a href="#">Terms and Conditions</a>
    	</div>
    </div>
    
</div>

