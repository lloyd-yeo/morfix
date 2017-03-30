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

class SendDmJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:send {offset : The position to start retrieving from.} {limit : The number of results to limit to.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out unfulfilled Direct Message jobs.';

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
    public function handle() {
        $offset = $this->argument('offset');
        $limit = $this->argument('limit');

        $users = DB::connection('mysql_old')->select("SELECT * FROM user ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);

        foreach ($users as $user) {
            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT id, insta_username, "
                    . "insta_pw, proxy, recent_activity_timestamp, insta_new_follower_template, follow_up_message FROM user_insta_profile "
                    . "WHERE auto_dm_new_follower = 1 AND checkpoint_required = 0"
                    . "AND account_disabled = 0 AND invalid_user = 0 AND incorrect_pw = 0 "
                    . "AND NOW() >= last_sent_dm  AND user_id = ?;", [$user->user_id]);

            foreach ($instagram_profiles as $ig_profile) {
                
                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;
                
                #$dm_job = DmJob::where("insta_username", "=", $ig_username)->where("fulfilled", '=', 0)->orderBy("time_to_send", "asc")->first();
                
                $dm_jobs = DB::connection('mysql_old')->select("SELECT job_id, recipient_username, recipient_insta_id, message FROM insta_affiliate.dm_job WHERE fulfilled = 0 AND insta_username = ? AND time_to_send <= NOW() ORDER BY job_id ASC LIMIT 1;", [$ig_profile->insta_username]);
                
                $config = array();
                $config["storage"] = "mysql";
                $config["dbusername"] = "root";
                $config["dbpassword"] = "inst@ffiliates123";
                $config["dbhost"] = "52.221.60.235:3306";
                $config["dbname"] = "morfix";
                $config["dbtablename"] = "instagram_sessions";

                $debug = false;
                $truncatedDebug = false;
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
                $instagram->setProxy($ig_profile->proxy);
                
                foreach ($dm_jobs as $dm_job) {
                    try {
                        $instagram->setUser($ig_username, $ig_password);
                        $explorer_response = $instagram->login();

                        $response = $instagram->directMessage($dm_job->recipient_insta_id, $dm_job->message);
                        $this->line(serialize($response));
                        if ($response->status == "ok") {
//                            $dm_job->fulfilled = 1;
//                            $dm_job->save();

                            //update profile's last sent dm timing
                            $next_send_time = \Carbon\Carbon::now();
                            $next_send_time->addMinute(rand(13, 15));
                            $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set last_sent_dm = ? where id = ?;', [$next_send_time, $ig_profile->id]);
                            $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set fulfilled = 1 where job_id = ?;', [$dm_job->job_id]);
                            
                        }
                    } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                        $this->line($checkpoint_ex->getMessage());
                    }
                }
            }
        }
    }
}
