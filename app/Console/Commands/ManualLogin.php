<?php

namespace App\Console\Commands;

use App\AddProfileRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\User;
use App\InstagramHelper;

class ManualLogin extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:login {ig_username?} {ig_password?} {proxy?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to Instagram.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $ig_username = $this->argument("ig_username");
	    $ig_password = $this->argument("ig_password");

	    $this->line($ig_username . " " . $ig_password);

	    $instagram = InstagramHelper::initInstagram();

	    $ig_profiles = collect();
	    $users = User::where('tier' , '>', 1)->get();
	    $valid_profile_count = 0;
	    $profile_count = 0;
	    foreach ($users as $user) {
	    	$ig_profiles = InstagramProfile::where('user_id', $user->user_id)->get();

		    foreach ($ig_profiles as $ig_profile) {
			    $profile_count++;
			    $guzzle_options = NULL;
			    if ($ig_profile->proxy == NULL) {
				    $this->info("Using RESIDENTIAL proxy.");
				    continue;
				    $guzzle_options                                 = [];
				    $guzzle_options['curl']                         = [];
				    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
				    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
				    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
			    } else if (strpos($ig_profile->proxy, 'http') === 0) {
				    $this->info("Using RESIDENTIAL proxy.");
				    continue;
				    $guzzle_options                                 = [];
				    $guzzle_options['curl']                         = [];
				    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
				    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
				    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
				    $ig_profile->proxy                              = NULL;
				    $ig_profile->save();
			    } else {
				    $this->info("Using DATACENTER proxy.");
				    $guzzle_options                                 = [];
				    $guzzle_options['curl']                         = [];
				    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
				    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'morfix:dXehM3e7bU';
				    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
			    }

			    $instagram->setGuzzleOptions($guzzle_options);
			    try {

				    $login_resp = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw, $guzzle_options);
				    if ($login_resp != NULL) {
					    dump($login_resp);
				    } else {
					    $user_model_public = $instagram->people->getSelfInfo()->getUser();
					    $ig_profile->profile_full_name = $user_model_public->getFullName();
					    $ig_profile->follower_count = $user_model_public->getFollowerCount();
					    $ig_profile->num_posts = $user_model_public->getMediaCount();
					    $ig_profile->save();
					    $valid_profile_count++;
					    dump($user_model_public);
					    dump($instagram->account->getCurrentUser()->getUser());
				    }

			    } catch (IncorrectPasswordException $incorrectPasswordException) {
				    $ig_profile->incorrect_pw = 1;
				    $ig_profile->save();
			    } catch (InstagramException $instagramException) {
					dump($instagramException);
			    } catch (NetworkException $networkException) {
				    dump($networkException);
			    }
		    }
	    }

	    $this->line("VALID PROFILE COUNT: " . $valid_profile_count);
	    $this->line("PROFILE COUNT: " . $profile_count);
    }

}
