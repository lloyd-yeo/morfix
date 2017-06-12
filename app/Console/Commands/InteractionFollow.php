<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\User;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionFollow extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:follow {email?}';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if (NULL !== $this->argument("email")) {
            $this->line('email: ' . $this->argument("email"));
            $users = User::where('email', $this->argument("email"))->get();
        } else {
            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
                    ->orderBy('user_id', 'asc')
                    ->get();
        }

        foreach ($users as $user) {

            $this->line($user->user_id);

            $instagram_profiles = InstagramProfile::whereRaw('(auto_follow = 1 OR auto_unfollow = 1) '
                    . 'AND checkpoint_required = 0'
                    . 'AND account_disabled = 0'
                    . 'AND invalid_user = 0'
                    . 'AND incorrect_pw = 0'
                    . 'AND (NOW() >= next_follow_time OR next_follow_time IS NULL)'
                    . 'AND user_id = ' . $user->user_id)
//                    ->where('checkpoint_required', false)
//                    ->where('account_disabled', false)
//                    ->where('invalid_user', false)
//                    ->where('incorrect_pw', false)
//                    ->where('user_id', $user->user_id)
//                    ->whereRaw('NOW() >= next_follow_time OR next_follow_time IS NULL')
                    ->get();

            foreach ($instagram_profiles as $ig_profile) {
                dispatch((new \App\Jobs\InteractionFollow(\App\InstagramProfile::find($ig_profile->id)))->onQueue('follows'));
                $this->line("queued profile: " . $ig_profile->insta_username);
                continue;
            }
        }
    }

}
