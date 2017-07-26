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

        if (NULL !== $this->argument("email")) {
            $users = User::where('email', $this->argument("email"))->get();
        } else {
            $users = DB::table('user')
                    ->whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
                    ->orderBy('user_id', 'asc')
                    ->get();
        }

        foreach ($users as $user) {
            $instagram_profiles = InstagramProfile::where('checkpoint_required', false)
                    ->where('account_disabled', false)
                    ->where('invalid_user', false)
                    ->where('incorrect_pw', false)
                    ->where('user_id', $user->user_id)
                    ->get();

            foreach ($instagram_profiles as $ig_profile) {
                $job = new \App\Jobs\RefreshIgProfile(\App\InstagramProfile::find($ig_profile->id));
                $job->onQueue('refresh');
                dispatch($job);
                $this->line("queued profile: " . $ig_profile->insta_username);
            }
        }

        return;
    }

}
