<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use Illuminate\Console\Command;
use App\InstagramHelper;

class RefreshInstagramSessionDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Instagram sessions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$users = User::where('tier', '>', 1)->where('partition', 0)->get();
    	foreach ($users as $user) {
		    $instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
			$instagram = InstagramHelper::initInstagram();

		    foreach ($instagram_profiles as $instagram_profile) {
		    	$proxy = $instagram_profile->proxy;

			    if (strpos($proxy, 'http') === 0) {
				    $guzzle_options                                 = [];
				    $guzzle_options['curl']                         = [];
				    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
				    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
				    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
				    $instagram->setGuzzleOptions($guzzle_options);
					$this->line("[DEBUG] Attempting to login for " . $instagram_profile->insta_username . '...');
				    $instagram_profile->proxy = NULL;
				    $instagram_profile->save();

				    $login_resp = $instagram->login($instagram_profile->insta_username, $instagram_profile->insta_pw, $guzzle_options);

				    dump($login_resp);

				    if ($login_resp->isOk()) {
					    $instagram_profile->proxy = InstagramHelper::getDatacenterProxyList()[rand(0,99)];
					    $instagram_profile->save();
					    $this->line("[DEBUG] Assigned new proxy to " . $instagram_profile->insta_username . '.');
				    }
			    }
		    }
	    }

    }
}
