<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\InstagramProfileFollowLog;
use App\InstagramProfile;

class MigrateFollowLogs extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:follow {insta_username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate follow logs for this slave.';

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
        if ($this->argument('insta_username') === NULL) {

            $ig_profiles = InstagramProfile::all();

            foreach ($ig_profiles as $ig_profile) {

//                $master_follow_logs = DB::connection('mysql_master')
//                        ->table('user_insta_profile_follow_log')
//                        ->where('insta_username', $ig_profile->insta_username)
//                        ->get();

                DB::connection('mysql_master')->table('user_insta_profile_follow_log')
                        ->where('insta_username', $ig_profile->insta_username)->chunk(1000, function ($master_follow_logs) {
                    foreach ($master_follow_logs as $master_follow_log) {
                        $follow_log = new InstagramProfileFollowLog;
                        $follow_log->log_id = $master_follow_log->log_id;
                        $follow_log->insta_username = $master_follow_log->insta_username;
                        $follow_log->follower_username = $master_follow_log->follower_username;
                        $follow_log->follower_id = $master_follow_log->follower_id;
                        $follow_log->log = $master_follow_log->log;
                        $follow_log->date_inserted = $master_follow_log->date_inserted;
                        $follow_log->added_delay = $master_follow_log->added_delay;
                        $follow_log->follow = $master_follow_log->follow;
                        $follow_log->follow_success = $master_follow_log->follow_success;
                        $follow_log->unfollowed = $master_follow_log->unfollowed;
                        $follow_log->unfollow_log = $master_follow_log->unfollow_log;
                        $follow_log->date_unfollowed = $master_follow_log->date_unfollowed;
                        try {
                            $follow_log->save();
                            $this->line("[Follow Log] Saving log id: " . $follow_log->log_id);
                        } catch (QueryException $ex) {
                            $this->line($ex->getMessage());
                        }
                    }
                });
            }
        } else {
            $ig_profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();

            $master_follow_logs = DB::connection('mysql_master')
                    ->table('user_insta_profile_follow_log')
                    ->where('insta_username', $ig_profile->insta_username)
                    ->get();

            foreach ($master_follow_logs as $master_follow_log) {
                $follow_log = new InstagramProfileFollowLog;
                $follow_log->log_id = $master_follow_log->log_id;
                $follow_log->insta_username = $master_follow_log->insta_username;
                $follow_log->follower_username = $master_follow_log->follower_username;
                $follow_log->follower_id = $master_follow_log->follower_id;
                $follow_log->log = $master_follow_log->log;
                $follow_log->date_inserted = $master_follow_log->date_inserted;
                $follow_log->added_delay = $master_follow_log->added_delay;
                $follow_log->follow = $master_follow_log->follow;
                $follow_log->follow_success = $master_follow_log->follow_success;
                $follow_log->unfollowed = $master_follow_log->unfollowed;
                $follow_log->unfollow_log = $master_follow_log->unfollow_log;
                $follow_log->date_unfollowed = $master_follow_log->date_unfollowed;
                try {
                    $follow_log->save();
                } catch (QueryException $ex) {
                    $this->line($ex->getMessage());
                }
            }
        }
    }

}
