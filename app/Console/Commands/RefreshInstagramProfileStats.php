<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\InstagramProfile;
use InstagramAPI\Exception\InstagramException;
use InstagramAPI\Exception\NetworkException;
use InstagramAPI\Instagram;
use App\InstagramHelper;

class RefreshInstagramProfileStats extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'refresh:igprofile';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Refresh the stats of all instagram profiles';

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
		$users     = User::where('tier', '>', 1)
		                 ->where('vip', 0)
		                                        ->where('admin', 0)
		                                        ->where('partition', 0)->get();
		$instagram = InstagramHelper::initInstagram();
		foreach ($users as $user) {
			$instagram_profiles = InstagramProfile::where('challenge_required', 0)->where('user_id', $user->user_id)->get();
			foreach ($instagram_profiles as $ig_profile) {
				$this->line("[REFRESH] " . $user->email . " " . $ig_profile->insta_username . " " . $ig_profile->insta_pw);
				$guzzle_options = NULL;
				if ($ig_profile->proxy == NULL) {
					$this->info("Using RESIDENTIAL proxy.");
					$guzzle_options                                 = [];
					$guzzle_options['curl']                         = [];
					$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
					$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
					$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
				} else if (strpos($ig_profile->proxy, 'http') === 0) {
					$this->info("Using RESIDENTIAL proxy.");
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
						$user_model_public             = $instagram->people->getSelfInfo()->getUser();
						$ig_profile->profile_full_name = $user_model_public->getFullName();
						$ig_profile->follower_count    = $user_model_public->getFollowerCount();
						$ig_profile->num_posts         = $user_model_public->getMediaCount();
						$ig_profile->save();
					}
				} catch (NetworkException $networkException) {
					$this->line($networkException->getMessage());
					$this->line($networkException->getTraceAsString());
				} catch (InstagramException $instagramException) {
					$this->line($instagramException->getMessage());
					$this->line($instagramException->getTraceAsString());
				}

			}
		}

	}
}
