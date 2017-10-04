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

class GetDm extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:get {email?} {queueasjob?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new followers and populate the retrieved user\'s dm queue with new jobs.';

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
        if ($this->argument("email") === NULL) { //master
            $users = User::where('partition', 0)->get();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\GetDm(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('getdm');
                    dispatch($job);
                    $this->line("[GetDM] Queued Profile: " . $ig_profile->insta_username);
                }
            }
        } else if ($this->argument("email") === "slave") { //slave
            $users = User::all();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\GetDm(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('getdm');
                    dispatch($job);
                    $this->line("[GetDM] Queued Profile: " . $ig_profile->insta_username);
                }
            }
        } else if ($this->argument("email") !== NULL && $this->argument("queueasjob") !== NULL) {
            $email = $this->argument("email");
            $users = User::where('email', $email)->get();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\GetDm(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('getdm');
                    dispatch($job);
                    $this->line("[GetDM] Queued Profile: " . $ig_profile->insta_username);
                }
            }
        } else if ($this->argument("email") !== NULL) {
            $email = $this->argument("email");
            //run job manually.
        }
    }

}
