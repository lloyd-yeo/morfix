<?php

namespace App\Console\Commands;

use App\Mail\VerifyAccount;
use Mail;
use Illuminate\Console\Command;
use App\User;
use App\InstagramProfile;
use Carbon\Carbon;

class SendVerifyProfileEmail extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'send:profileverify';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send profile verify email.';

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
		$users = User::where('tier', '>', 1)
					 ->where('verify_profile_notification_last_sent', '>', Carbon::now()->addHours(2))
		             ->get();

		foreach ($users as $user) {
			$instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
			$send_mail          = FALSE;

			foreach ($instagram_profiles as $instagram_profile) {
				if ($instagram_profile->challenge_required == 1) {
					$send_mail = TRUE;
					break;
				}
			}

			try {
				if ($send_mail) {
					Mail::to($user)->send(new VerifyAccount($user));
					$user->verify_profile_notification_last_sent = Carbon::now();
				} else {
					$user->verify_profile_notification_last_sent = NULL;
				}
				$user->save();
			} catch (\Exception $ex) {
				dump($ex);
			}
		}
	}
}
