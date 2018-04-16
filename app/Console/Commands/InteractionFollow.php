<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\Niche;
use App\NicheTarget;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\InstagramHelper;
use Carbon\Carbon;

class InteractionFollow extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:follow {email?} {queueasjob?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Follow user\'s intended targets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     *
     *  Local Variables
     *
     */
    protected $ig_username;
    protected $ig_password;
    protected $insta_username;
    protected $insta_user_id;
    protected $insta_id;
    protected $insta_pw;
    protected $niche;
    protected $next_follow_time; //
    protected $unfollow; // Not use
    protected $follow_cycle;
    protected $auto_unfollow;
    protected $auto_follow;
    protected $auto_follow_ban;
    protected $auto_follow_ban_time;
    protected $follow_unfollow_delay;
    protected $speed;
    protected $follow_min_follower;
    protected $follow_max_follower;
    protected $unfollow_unfollowed;
    protected $follow_quota;
    protected $unfollow_quota;
    protected $proxy;
    protected $delay;
    protected $use_hashtags;
    protected $target_hashtags;
    protected $target_usernames;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $users = NULL;

        if ($this->argument("email") == "ig") {

	        $instagram_profiles = InstagramProfile::whereRaw('(auto_follow = 1 OR auto_unfollow = 1) '
		        . 'AND insta_username = \'' . $this->argument("queueasjob") . '\'')->get();

	        foreach ($instagram_profiles as $ig_profile) {

		        if (!InstagramHelper::validForInteraction($ig_profile)) {
			        continue;
		        }

		        if ($ig_profile->auto_follow_ban == 1 && $ig_profile->auto_follow_ban_time === NULL) {
			        $ig_profile->auto_follow_ban = 0;
			        $ig_profile->save();
		        }

		        if ($ig_profile->auto_follow_ban == 1 && \Carbon\Carbon::now()->lt(new \Carbon\Carbon($ig_profile->next_follow_time))) {
			        $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Follow & the ban isn't lifted yet.");
			        continue;
		        }

		        if ($ig_profile->next_follow_time === NULL) {
			        $ig_profile->next_follow_time = \Carbon\Carbon::now();
			        $ig_profile->save();
			        dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
				        ->onQueue('follows')
			            ->onConnection('sync'));
			        $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
		        } else if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_follow_time))) {
			        dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
				        ->onQueue('follows')
				        ->onConnection('sync'));
			        $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
		        }
	        }

        	return;
        }


        if ($this->argument("email") == "slave") {
            $this->info("[Follow Interaction] Queueing jobs for Slave.");
            $users = User::all();
        } else if (NULL !== $this->argument("email")) {
            $this->info("[Follow Interaction] Manually executing follow for " . $this->argument("email"));
            $users = User::where('email', $this->argument("email"))->get();
        } else {
            $this->info("[Follow Interaction] Queueing jobs for Master.");
            $users = User::where('partition', 0)
                    ->orderBy('user_id', 'ASC')
                    ->get();
        }

        foreach ($users as $user) {

//            $this->line($user->user_id);

            $instagram_profiles = InstagramProfile::whereRaw('(auto_follow = 1 OR auto_unfollow = 1) '
                            . 'AND user_id = ' . $user->user_id)->get();

            //Queueing for Master & Slave without Email
            if (NULL === $this->argument("email") || $this->argument("email") == "slave") {
                if ($user->tier > 1 || $user->trial_activation == 1) {
                    foreach ($instagram_profiles as $ig_profile) {

                        if (!InstagramHelper::validForInteraction($ig_profile)) {
                            continue;
                        }

                        if ($ig_profile->auto_follow_ban == 1 && $ig_profile->auto_follow_ban_time === NULL) {
	                        $ig_profile->auto_follow_ban = 0;
	                        $ig_profile->save();
                        }

                        if ($ig_profile->auto_follow_ban == 1 && Carbon::now()->lt($ig_profile->next_follow_time)) {
                            $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Follow & the ban isn't lifted yet.");
                            continue;
                        }

                        if ($ig_profile->next_follow_time === NULL) {
                            $ig_profile->next_follow_time = \Carbon\Carbon::now();
                            $ig_profile->save();
                            dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
                                            ->onQueue('follows'));
                            $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
                        } else if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_follow_time))) {
                            dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
                                            ->onQueue('follows'));
                            $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
                        }
                    }
                }
                //Else, queueing for Email.
            } else {

                foreach ($instagram_profiles as $ig_profile) {

                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }

	                if ($ig_profile->auto_follow_ban == 1 && Carbon::now()->lt($ig_profile->next_follow_time)) {
		                $this->error("[" . $ig_profile->insta_username . "] is throttled on Auto Follow & the ban isn't lifted yet.");
		                continue;
	                }

                    if ($this->argument("queueasjob") !== NULL) {
                        if ($ig_profile->next_follow_time === NULL) {
                            $this->warn("[" . $ig_profile->insta_username . "] next_follow_time is NULL.");
                            $ig_profile->next_follow_time = \Carbon\Carbon::now();
                            $ig_profile->save();
                            dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
                                            ->onQueue('follows'));
                            $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
                        } else if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_follow_time))) {
                            dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
                                            ->onQueue('follows'));
                            $this->line("[Follow Interactions] queued " . $ig_profile->insta_username);
                        }
                    } else {
	                    $this->line("[Follow Interactions] manually queued " . $ig_profile->insta_username);
	                    dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))
		                    ->onQueue('follows')->onConnection("sync"));

                    }
                }
            }
        }
    }
}
