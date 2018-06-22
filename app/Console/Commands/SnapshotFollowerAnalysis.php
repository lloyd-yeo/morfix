<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SnapshotFollowerAnalysis extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'snapshot:follower';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Take a snapshot of the followers and store it.';

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
		DB::insert("INSERT INTO user_insta_follower_analysis(insta_username, follower_count) 
							SELECT insta_username, follower_count FROM user_insta_profile 
							WHERE invalid_user = 0 AND checkpoint_required = 0 AND account_disabled = 0 AND incorrect_pw = 0;");
		DB::update("UPDATE user SET engagement_quota = 1;");
	}
}
