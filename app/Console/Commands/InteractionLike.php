<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\User;
use App\InstagramProfile;
use App\InstagramHelper;

class InteractionLike extends Command
{

	use DispatchesJobs;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'interaction:like {email?} {queueasjob?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Like photos of user\'s intended targets.';

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

		if (NULL === $this->argument("email")) { #master
			$this->line("[Likes Interaction Master] Beginning sequence to queue jobs...");

			$users = User::where('partition', 0)
				->orderBy('user_id', 'asc')
				->get();

			$this->dispatchJobsToEligibleUsers($users);
		} else {
			if ($this->argument("email") !== NULL && $this->argument("queueasjob") !== NULL) {

				$this->line("[Likes Interaction Email] Queueing job for [" . $this->argument("email") . "]");

				$user = User::where('email', $this->argument("email"))->first();

				if ($user !== NULL) {

					if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

						$instagram_profiles = InstagramProfile::where('auto_like', TRUE)
							->where('user_id', $user->user_id)
							->get();

						foreach ($instagram_profiles as $ig_profile) {

							if (!InstagramHelper::validForInteraction($ig_profile)) {
								continue;
							}

							if ($ig_profile->auto_like_ban == 1 && Carbon::now()->lt(Carbon::parse($ig_profile->next_like_time))) {
								$this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
								continue;
							}

							if ($ig_profile->next_like_time === NULL) {
								$ig_profile->next_like_time = Carbon::now();
								$ig_profile->save();
								$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
								$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
								$job->onQueue("likes");
								dispatch($job);
							} else {
								if (Carbon::now()->gte(Carbon::parse($ig_profile->next_like_time))) {
									$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
									$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
									$job->onQueue("likes");
									dispatch($job);
								}
							}
						}
					} else {
						$this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
					}
				} else {
					$this->line("[" . $this->argument("email") . "] user not found.");
				}
			} else {
				if ($this->argument("email") == "slave") {

					$this->line("[Likes Interaction Slave] Beginning sequence to queue jobs...");

					$users = User::orderBy('user_id', 'asc')
						->get();

					$this->dispatchJobsToEligibleUsers($users);
				} else {
					$this->line("[Likes Interaction Email Manual] Beginning sequence for [" . $this->argument("email") . "]");

					$user = User::where('email', $this->argument("email"))->first();

					// microtime(true) returns the unix timestamp plus milliseconds as a float
					$starttime = microtime(TRUE);

					if ($user !== NULL) {
						$this->dispatchManualJobToUser($user);
					}

					$endtime = microtime(TRUE);
					$timediff = $endtime - $starttime;

					echo "\nThis run took: $timediff milliseconds.\n";
				}
			}
		}
	}

	private function dispatchManualJobToUser($user)
	{
		if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

			$instagram_profiles = InstagramProfile::where('auto_like', TRUE)
				->where('user_id', $user->user_id)
				->get();

			foreach ($instagram_profiles as $ig_profile) {

				$this->line("[" . $ig_profile->insta_username . "] next_like_time [" . $ig_profile->next_like_time . "] [" . $ig_profile->like_quota . "]");

				if (!InstagramHelper::validForInteraction($ig_profile)) {
					continue;
				}

				if ($ig_profile->auto_like_ban == 1 &&
					Carbon::now()->lt(Carbon::parse($ig_profile->next_like_time))) {
					$this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
//					continue;
				}

				if ($ig_profile->next_like_time === NULL) {
					$ig_profile->next_like_time = Carbon::now();
					$ig_profile->save();
					$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
					$job->onQueue("likes");
					$job->onConnection('sync');
					dispatch($job);
					$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
				} else {
					if (Carbon::now()->gte(Carbon::parse($ig_profile->next_like_time))) {
						$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
						$job->onQueue("likes");
						$job->onConnection('sync');
						dispatch($job);
						$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
					} else {
						if (Carbon::now()->lt(Carbon::parse($ig_profile->next_like_time))) {
							$this->line("[" . $ig_profile->insta_username . "] unable to queue because of next_like_time [Likes]");
						}
					}
				}
			}
		} else {
			$this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
		}
	}

	private function dispatchJobsToEligibleUsers($users)
	{
		foreach ($users as $user) {

			if (($user->tier == 1 && $user->trial_activation == 1) || $user->tier > 1) {

				$instagram_profiles = InstagramProfile::where('auto_like', TRUE)->where('user_id', $user->user_id)
					->get();

				foreach ($instagram_profiles as $ig_profile) {

					if (!InstagramHelper::validForInteraction($ig_profile)) {
						continue;
					}

					if ($ig_profile->auto_like_ban == 1 && Carbon::now()->lt(Carbon::parse($ig_profile->next_like_time))) {
						$this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Likes & the ban isn't time yet.");
						continue;
					}

					if ($ig_profile->next_like_time === NULL) {
						$ig_profile->next_like_time = Carbon::now();
						$ig_profile->save();
						$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
						$job->onQueue("likes");
						dispatch($job);
						$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
					} else {
						if (Carbon::now()->gte(Carbon::parse($ig_profile->next_like_time))) {
							$job = new \App\Jobs\InteractionLike(\App\InstagramProfile::find($ig_profile->id));
							$job->onQueue("likes");
							dispatch($job);
							$this->line("[" . $ig_profile->insta_username . "] queued for [Likes]");
						}
					}
				}
			} else {
				$this->line("[" . $user->email . "] is not on Premium Tier or Free-Trial");
			}
		}
	}

}
