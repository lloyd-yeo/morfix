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

class GetDmJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:get {email?}';

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
        if (NULL !== $this->argument("email")) {
            $users = DB::connection('mysql_old')->select("SELECT u.user_id, u.email, user_tier FROM insta_affiliate.user u WHERE u.email = ?;", [$this->argument("email")]);
        } else {
            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile WHERE auto_dm_new_follower = 1)')
                    ->orderBy('user_id', 'desc')
                    ->get();
            
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                                                        ->get();
                
                foreach ($instagram_profiles as $ig_profile) {
                    
                }
            }
            
        }
    }
}
