<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\User;
use App\InstagramProfile;

class TurnOffInteractions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interactions:turnoff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn off interactions for free-trial users that are no longer on free-trial.';

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
        $users = User::where('tier', 1)->whereDate('trial_end_date', '<', Carbon::now())->get();
        foreach ($users as $user) {
		    $user->trial_activation = 2;

		    if ($user->save()) {
		    	$this->line("[" . $user->email . "] trial activation has expired.");
		    }

	        $instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();

	        foreach ($instagram_profiles as $instagram_profile) {
		        $instagram_profile->auto_like = 0;
		        $instagram_profile->auto_follow = 0;
		        $instagram_profile->auto_comment = 0;
		        $instagram_profile->auto_unfollow = 0;
		        $instagram_profile->auto_dm_new_follower = 0;
		        $instagram_profile->proxy = NULL;
		        if ($instagram_profile->save()) {
			        $this->line("[" . $instagram_profile->insta_username . "] all interactions has been turned off.");
		        }
	        }
	    }
    }
}
