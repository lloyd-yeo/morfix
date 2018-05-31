<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

class RefreshInteractionsQuota extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:interactionsquota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to refresh Interactions Quota for IG Profile every hour.';

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
        DB::table('user_insta_profile')
                ->update(['follow_quota' => 22,
                    'unfollow_quota' => 22,
                    'like_quota' => 30,
                    'comment_quota' => 6]);

        DB::table('user_insta_profile')
	        ->where(Carbon::now(), '>', 'throttled_date')
	        ->where('ig_throttled', 1)
	        ->update(['ig_throttled' => 0,
		        'throttled_date', NULL]);
    }

}
