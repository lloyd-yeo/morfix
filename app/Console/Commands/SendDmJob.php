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

class SendDmJob extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:send {email?} {queueasjob?}';

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

                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }

                    if ($ig_profile->temporary_ban !== NULL || $ig_profile->dm_probation !== 0) {
                        $this->error("[" . $ig_profile->insta_username . "] is either on temporary ban/suspension.");
                        continue;
                    }

                    if (\Carbon\Carbon::now()->gte($ig_profile->last_sent_dm)) {
                        $job = new \App\Jobs\SendDm(\App\InstagramProfile::find($ig_profile->id));
                        $job->onQueue('senddm');
                        dispatch($job);
                        $this->line("Queued Profile: " . $ig_profile->insta_username);
                    } else {
                        $this->info("[" . $ig_profile->insta_username . "] is not ready to send DMs yet.");
                    }
                }
            }
        } else if ($this->argument("email") === "slave") { //slave
            $users = User::all();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {

                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }

                    if ($ig_profile->temporary_ban !== NULL || $ig_profile->dm_probation !== 0) {
                        $this->error("[" . $ig_profile->insta_username . "] is either on temporary ban/suspension.");
                        continue;
                    }

                    if (\Carbon\Carbon::now()->gte($ig_profile->last_sent_dm)) {
                        $job = new \App\Jobs\SendDm(\App\InstagramProfile::find($ig_profile->id));
                        $job->onQueue('senddm');
                        dispatch($job);
                        $this->line("Queued Profile: " . $ig_profile->insta_username);
                    } else {
                        $this->info("[" . $ig_profile->insta_username . "] is not ready to send DMs yet.");
                    }
                }
            }
        } else if ($this->argument("email") !== NULL && $this->argument("queueasjob") !== NULL) {
            $email = $this->argument("email");
            $users = User::where('email', $email)->get();
            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {

                    if (!InstagramHelper::validForInteraction($ig_profile)) {
                        continue;
                    }

                    if ($ig_profile->temporary_ban !== NULL || $ig_profile->dm_probation !== 0) {
                        $this->error("[" . $ig_profile->insta_username . "] is either on temporary ban/suspension.");
                        continue;
                    }

                    if (\Carbon\Carbon::now()->gte($ig_profile->last_sent_dm)) {
                        $job = new \App\Jobs\SendDm(\App\InstagramProfile::find($ig_profile->id));
                        $job->onQueue('senddm');
                        dispatch($job);
                        $this->line("Queued Profile: " . $ig_profile->insta_username);
                    } else {
                        $this->info("[" . $ig_profile->insta_username . "] is not ready to send DMs yet.");
                    }
                }
            }
        } else if ($this->argument("email") !== NULL) {
            $email = $this->argument("email");
            //run job manually.
        }

        exit();

        $users = array();
        if (NULL !== $this->argument("email")) {
            $users = User::where('email', $this->argument("email"))
                    ->orderBy('user_id', 'desc')
                    ->get();

            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\SendDm(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('senddm');
                    dispatch($job);
                    $this->line("Queued Profile: " . $ig_profile->insta_username);
                }
            }
        } else {
            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile WHERE partition = 0 AND auto_dm_new_follower = 1)')
                    ->orderBy('user_id', 'desc')
                    ->get();

            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();

                foreach ($instagram_profiles as $ig_profile) {
                    $job = new \App\Jobs\SendDm(\App\InstagramProfile::find($ig_profile->id));
                    $job->onQueue('senddm');
                    dispatch($job);
                    $this->line("Queued Profile: " . $ig_profile->insta_username);
                }
            }
        }
        
        exit();


        $offset = $this->argument('offset');
        $limit = $this->argument('limit');

        $execute_script = 1;
        $execute_flags = DB::connection('mysql_old')->select("SELECT * FROM morfix_settings WHERE setting = 'dm_offset' AND value = ?;", [$offset]);
        foreach ($execute_flags as $execute_flag) {
            $execute_script = 0;
        }

        if ($execute_script == 1) {
            DB::connection('mysql_old')->insert("INSERT INTO morfix_settings (setting, value) VALUES (?,?);", ['dm_offset', $offset]);
        } else {
            exit();
        }

        if (NULL !== $this->argument("email")) {
            $users = DB::connection('mysql_old')->select("SELECT * FROM insta_affiliate.user u WHERE u.email = ?;", [$this->argument("email")]);
        } else {
            $users = DB::connection('mysql_old')->select("SELECT * FROM user ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);
        }

//        $users = DB::connection('mysql_old')->select("SELECT * FROM user ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);

        foreach ($users as $user) {

            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT id, insta_username, "
                    . "insta_pw, proxy, recent_activity_timestamp, insta_new_follower_template, follow_up_message, auto_dm_delay FROM user_insta_profile "
                    . "WHERE auto_dm_new_follower = 1 AND checkpoint_required = 0 "
                    . "AND account_disabled = 0 AND invalid_user = 0 AND incorrect_pw = 0 "
                    . "AND (NOW() >= last_sent_dm OR last_sent_dm IS NULL) AND user_id = ?;", [$user->user_id]);

            foreach ($instagram_profiles as $ig_profile) {

                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);

                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

                #$dm_job = DmJob::where("insta_username", "=", $ig_username)->where("fulfilled", '=', 0)->orderBy("time_to_send", "asc")->first();

                $dm_jobs = DB::connection('mysql_old')->select("SELECT job_id, recipient_username, recipient_insta_id, message, follow_up_order "
                        . "FROM insta_affiliate.dm_job WHERE fulfilled = 0 AND insta_username = ? AND time_to_send <= NOW() AND message > '' "
                        . "ORDER BY job_id ASC LIMIT 1;", [$ig_profile->insta_username]);

                $config = array();
                $config["storage"] = "mysql";
                $config["dbusername"] = "root";
                $config["dbpassword"] = "inst@ffiliates123";
                $config["dbhost"] = "52.221.60.235:3306";
                $config["dbname"] = "morfix";
                $config["dbtablename"] = "instagram_sessions";

                $debug = true;
                $truncatedDebug = false;
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
                if ($ig_profile->proxy === NULL) {
                    $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 2;");
                    foreach ($proxies as $proxy) {
                        $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
                        $instagram->setProxy($proxy->proxy);
                        $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);
                    }
                } else {
                    $instagram->setProxy($ig_profile->proxy);
                }

                foreach ($dm_jobs as $dm_job) {
                    if ($dm_job->follow_up_order == 1 && $user->user_tier <= 3) {
                        $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set fulfilled = 1, success_msg = ?, updated_at = NOW() where job_id = ?;', ["Not a Business user.", $dm_job->job_id]);
                        continue;
                    }

                    try {
                        $explorer_response = $instagram->login($ig_username, $ig_password);
                        $response = $instagram->directMessage($dm_job->recipient_insta_id, $dm_job->message);
                        $this->line(serialize($response));
                        if ($response->status == "ok") {
//                            $dm_job->fulfilled = 1;
//                            $dm_job->save();
                            //update profile's last sent dm timing
                            $delay = rand(30, 40);
                            $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set last_sent_dm = NOW() + INTERVAL ' . $delay . ' MINUTE where id = ?;', [$ig_profile->id]);
                            $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set fulfilled = 1, success_msg = ?, updated_at = NOW() where job_id = ?;', [serialize($response), $dm_job->job_id]);

                            //make sure follow up sends a day later.
                            if ($ig_profile->auto_dm_delay == 1) {
                                DB::connection('mysql_old')->update("UPDATE dm_job SET time_to_send = NOW() + INTERVAL 1 DAY "
                                        . "WHERE insta_username = ? AND recipient_insta_id = ? AND fulfilled = 0;", [$ig_profile->insta_username, $dm_job->recipient_insta_id]);
                            }
                        }
                    } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                        $this->line($checkpoint_ex->getMessage());
                        $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                        $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set error_msg = ?, updated_at = NOW() where job_id = ?;', [$checkpoint_ex->getMessage(), $dm_job->job_id]);
                    } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                        $this->line($network_ex->getMessage());
                        $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set error_msg = ?, updated_at = NOW() where job_id = ?;', [$network_ex->getMessage(), $dm_job->job_id]);
                    } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                        $this->line($endpoint_ex->getMessage());
                        $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set error_msg = ?, updated_at = NOW() where job_id = ?;', [$endpoint_ex->getMessage(), $dm_job->job_id]);
                    } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedbackrequired_ex) {
                        $this->line($feedbackrequired_ex->getMessage());
                        $rows_affected = DB::connection('mysql_old')->update("UPDATE user_insta_profile SET last_sent_dm = NOW() + INTERVAL 1 DAY, temporary_ban = NOW() + INTERVAL 4 HOUR WHERE insta_username = ?;", [$ig_profile->insta_username]);
                        $rows_affected_msg = DB::connection('mysql_old')->update('update dm_job set error_msg = ?, updated_at = NOW() where job_id = ?;', [$feedbackrequired_ex->getMessage(), $dm_job->job_id]);
                    }
                }
            }
        }
        DB::connection('mysql_old')->delete("DELETE FROM morfix_settings WHERE setting = 'dm_offset' AND value = ?;", [$offset]);
    }

}
