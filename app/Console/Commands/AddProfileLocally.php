<?php

namespace App\Console\Commands;

use App\AddProfileRequest;
use Illuminate\Console\Command;

class AddProfileLocally extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'profile:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
		while (TRUE) {

			//0 means it is a new request.
			//1 means it is being worked on.
			//2 means it has a challenge_url.
			//3 means it has a challenge_url & the challenge_url has already been sent to the user.
			//4 means it has been cleared of challenge_url & should be re-attempted
			//6 means it is currently being attempted after clearing the challenge.
			$add_profile_requests = AddProfileRequest::where('working_on', 0)
			                                         ->orWhere('working_on', 4)
			                                         ->where('assignee', 0)
			                                         ->get();
			foreach ($add_profile_requests as $add_profile_request) {
				if ($add_profile_request->working_on == 0) {
					$add_profile_request->working_on = 1;
					$add_profile_request->save();
				} else if ($add_profile_request->working_on == 4) {
					$add_profile_request->working_on = 6;
					$add_profile_request->save();
				}


				$this->call('ig:login', [
					'ig_username'            => $add_profile_request->insta_username,
					'ig_password'            => $add_profile_request->insta_pw,
					'add_profile_request_id' => $add_profile_request->id,
				]);
			}
		}
	}
}
