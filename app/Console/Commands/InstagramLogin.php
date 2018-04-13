<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use Illuminate\Console\Command;
use \InstagramAPI\Response\LoginResponse;
use \InstagramAPI\Instagram;
use \InstagramAPI\Response\ChallengeSelectVerifyMethodStepResponse;
use \InstagramAPI\Response\GenericResponse;

class InstagramLogin extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'ig:lgin {username} {password} {challenge_url?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Manually login to Instagram';

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
		$instagram = InstagramHelper::initInstagram(TRUE);

		$guzzle_options                                 = [];
		$guzzle_options['curl']                         = [];
		$guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
		$guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
		$guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
		$instagram->setGuzzleOptions($guzzle_options);

		try {
			if ($this->argument('challenge_url') == NULL) {
				$login_response = $instagram->login($this->argument('username'),
					$this->argument('password'), $guzzle_options
				);
				dump($login_response);
			} else {
				$challenge_response = $this->makeRequestToChallengeUrl($instagram, $this->argument('username'), $this->argument('password'), $this->argument('challenge_url'));
				dump($challenge_response);

				if ($challenge_response->getStepName() == 'select_verify_method') {
					$choice         = $this->ask('We will need you to verify please select a way to get notified.');
					$select_verify_method_response = $this->selectVerifyMethod($instagram, $this->argument('username'), $this->argument('password'), $this->argument('challenge_url'), $choice);
					dump($select_verify_method_response);
					$challenge_response = $select_verify_method_response;
				}

				if ($challenge_response->getStepName() == 'verify_email' || $challenge_response->getStepName() == 'verify_phone') {
					$verification_code         = $this->ask('Please key in the 6 digit code sent by Instagram:');
					$finish_challenge_response = $this->finishChallengeVerification($instagram, $this->argument('username'), $this->argument('password'), $this->argument('challenge_url'), $verification_code);
					dump($finish_challenge_response);
				}
			}
		}
		catch (\Exception $ex) {
			dump($ex);
		}

		//	    $proxy = $this->getProxyList()[0];

		//	    $guzzle_options = array();
		//	    $guzzle_options['curl'] = array();
		//	    $guzzle_options['curl'][CURLOPT_PROXY] = $proxy;
		//	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD] = 'morfix:dXehM3e7bU';
		//	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;


		//	    $discoverResponse = $instagram->people->discoverPeople();
		//	    dump($discoverResponse);

	}

	public function makeRequestToChallengeUrl(
		$instagram,
		$username,
		$password,
		$challengeUrl)
	{
		if (empty($challengeUrl)) {
			throw new \InvalidArgumentException('You must provide a challenge url to makeRequestToChallengeUrl().');
		}
		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                 ->setNeedsAuth(FALSE)
		                 ->addParam('_csrftoken', $instagram->client->getToken())
		                 ->addParam('username', $username)
		                 ->addParam('device_id', $instagram->device_id)
		                 ->addParam('password', $password)
		                 ->getResponse(new ChallengeSelectVerifyMethodStepResponse());

		return $response;
	}

	public function selectVerifyMethod(
		$instagram,
		$username,
		$password,
		$challengeUrl,
		$choice) {

		if (empty($challengeUrl)) {
			throw new \InvalidArgumentException('You must provide a challenge url to selectVerifyMethod().');
		}
		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                 ->setNeedsAuth(FALSE)
		                 ->addPost('_csrftoken', $instagram->client->getToken())
		                 ->addPost('username', $username)
		                 ->addPost('device_id', $instagram->device_id)
		                 ->addPost('password', $password)
		                 ->addPost('choice', $choice)
		                 ->getResponse(new ChallengeSelectVerifyMethodStepResponse());

		return $response;
	}

	public function finishChallengeVerification(
		$instagram,
		$username,
		$password,
		$challengeUrl,
		$verificationCode) {

		if (empty($challengeUrl) || empty($verificationCode)) {
			throw new \InvalidArgumentException('You must provide a challenge url & verification code to finishChallengeVerification().');
		}
		$instagram->_setUser($username, $password);
		$response = $instagram->request($challengeUrl)
		                 ->setNeedsAuth(FALSE)
		                 ->addPost('_csrftoken', $instagram->client->getToken())
		                 ->addPost('username', $username)
		                 ->addPost('device_id', $instagram->device_id)
		                 ->addPost('password', $password)
		                 ->addPost('choice', 1)
		                 ->addPost('security_code', $verificationCode)
		                 ->getResponse(new GenericResponse());

		return $response;
	}


}
