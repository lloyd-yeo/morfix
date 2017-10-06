<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\User;
use App\InstagramHelper;

class RefreshInstagramProfile extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:refresh {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the stats for a user\'s instagram profile.';

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
        if ($this->argument("email") === NULL) {
            $users = User::where('active', 1)->get();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\RefreshIgProfile(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('refresh');
                    dispatch($job);
                    $this->line("[Refresh] Queued Profile: " . $ig_profile->insta_username);
                }
            }
        } else {
            $user = User::where('email', $this->argument("email"))->first();
            if ($user !== NULL) {
                $this->line("[" . $user->email . "] user found & processing...");
                $instagram_profiles = InstagramProfile::where('user_id', $user->user_id)->get();
                foreach ($instagram_profiles as $ig_profile) {
                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }
                    $job = new \App\Jobs\RefreshIgProfile(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('refresh');
                    $job->onConnection('sync');
                    $this->line("[Refresh] Queued Profile: " . $ig_profile->insta_username);
                    dispatch($job);
                }
            } else {
                $this->error("[" . $this->argument("email") . "] user not found.");
            }
        }
    }

}
