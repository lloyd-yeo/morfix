<?php

namespace App\Console\Commands;

use App\AddProfileRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\InstagramHelper;

class ManualLoginPrevious extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'ig:login2 {ig_username} {ig_password} {proxy?}';

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
	public function handle() {
		$ig_username = $this->argument("ig_username");
		$ig_password = $this->argument("ig_password");
		$instagram = InstagramHelper::initInstagram();

		$proxy = NULL;
		if ($this->argument("proxy") !== NULL) {
			$proxy = $this->argument("proxy");
			$instagram->setProxy($proxy);
		} else {
		}

		$this->line($ig_username . " " . $ig_password);

		try {
			dump($instagram->login($ig_username, $ig_password));
			dump($instagram->timeline->getSelfUserFeed());
		} catch (\InstagramAPI\Exception\ChallengeRequiredException $challenge_required_ex) {
			$challenge_url = $challenge_required_ex->getResponse()->asArray()["challenge"]["url"];
			dump($challenge_url);
		} catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
			dump($emptyresponse_ex);
		} catch (\InstagramAPI\Exception\InstagramException $instagramException) {
			dump($instagramException);
		}
	}

}
