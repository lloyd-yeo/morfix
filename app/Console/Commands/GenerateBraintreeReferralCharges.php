<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BraintreeTransaction;
use App\User;
use App\UserAffiliates;

class GenerateBraintreeReferralCharges extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'generate:braintreetransactions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate Braintree Referral Charges';

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
		$referred_user_under_braintree = User::whereNotNull('braintree_id')->get();


		foreach ($referred_user_under_braintree as $referred_user) {

//			dump($referred_user_under_braintree);continue;
			$this->line($referred_user);
			$referrer_id = NULL;

			$user_affiliate = UserAffiliates::where('referred', $referred_user->id)->whereNotNull('referrer')->first();
			dump($user_affiliate);
//			if ($user_affiliate != NULL) {
//				dump($user_affiliate);
//				$referrer_user = User::find($user_affiliate->referrer);
//				if ($referrer_user != NULL) {
//					$this->line($referred_user->email . " is referred by: " . $referrer_user->email);
//				}
//			}
		}
	}
}
