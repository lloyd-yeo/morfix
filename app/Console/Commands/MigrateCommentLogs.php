<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\InstagramProfileCommentLog;
use App\InstagramProfile;

class MigrateCommentLogs extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:commentlogs {insta_username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate comment logs for this slave.';

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
                $master_comment_logs = DB::connection('mysql_master')
                        ->table('user_insta_profile_comment_log')
                        ->where('insta_username', $ig_profile->insta_username)
                        ->where('date_commented', '>', '2017-08-01 00:00:00')
                        ->orderBy('date_commented', 'desc')
                        ->get();

                foreach ($master_comment_logs as $master_comment_log) {
                    $comment_log = new InstagramProfileCommentLog;
                    $comment_log->log_id = $master_comment_log->log_id;
                    $comment_log->insta_username = $master_comment_log->insta_username;
                    $comment_log->target_username = $master_comment_log->target_username;
                    $comment_log->target_insta_id = $master_comment_log->target_insta_id;
                    $comment_log->target_media = $master_comment_log->target_media;
                    $comment_log->log = $master_comment_log->log;
                    $comment_log->date_commented = $master_comment_log->date_commented;
                    try {
                        $comment_log->save();
                    } catch (QueryException $ex) {
                        continue;
                    }
                }
            }
        } else {
            $ig_profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();

            $master_comment_logs = DB::connection('mysql_master')
                    ->table('user_insta_profile_comment_log')
                    ->where('insta_username', $ig_profile->insta_username)
                    ->get();

            foreach ($master_comment_logs as $master_comment_log) {
                $comment_log = new InstagramProfileCommentLog;
                $comment_log->log_id = $master_comment_log->log_id;
                $comment_log->insta_username = $master_comment_log->insta_username;
                $comment_log->target_username = $master_comment_log->target_username;
                $comment_log->target_insta_id = $master_comment_log->target_insta_id;
                $comment_log->target_media = $master_comment_log->target_media;
                $comment_log->log = $master_comment_log->log;
                $comment_log->date_commented = $master_comment_log->date_commented;
                try {
                    $comment_log->save();
                } catch (QueryException $ex) {
                    continue;
                }
            }
        }
    }

}
