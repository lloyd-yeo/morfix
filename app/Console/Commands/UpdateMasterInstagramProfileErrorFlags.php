<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use Illuminate\Console\Command;
use DB;

class UpdateMasterInstagramProfileErrorFlags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:errorflag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Write on to master the error flags from Slave.';

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
    	foreach (InstagramProfile::all() as $ig_profile) {
    		$checkpoint_required = $ig_profile->checkpoint_required;
    		$account_disabled = $ig_profile->account_disabled;
		    $invalid_user = $ig_profile->invalid_user;
		    $incorrect_pw = $ig_profile->incorrect_pw;
		    $feedback_required = $ig_profile->feedback_required;
		    $auto_like_ban = $ig_profile->auto_like_ban;
		    $auto_comment_ban = $ig_profile->auto_comment_ban;
		    $auto_follow_ban = $ig_profile->auto_follow_ban;
		    $auto_unfollow_ban = $ig_profile->auto_unfollow_ban;

		    DB::connection('mysql_master')->table('user_insta_profile')->where('insta_username', $ig_profile->insta_username)
			    ->update([
				'checkpoint_required' => $checkpoint_required,
			    'account_disabled' => $account_disabled,
			    'invalid_user' => $invalid_user,
			    'incorrect_pw' => $incorrect_pw,
			    'feedback_required' => $feedback_required,
			    'auto_like_ban' => $auto_like_ban,
			    'auto_comment_ban' => $auto_comment_ban,
			    'auto_follow_ban' => $auto_follow_ban,
			    'auto_unfollow_ban' => $auto_unfollow_ban,
		    ]);
	    }
    }
}
