<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class GetDm implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $profile;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();
        
        $ig_profile = $this->profile;
        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;
        $user = $ig_profile->owner();
        
        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        
        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

        if ($ig_profile->proxy === NULL) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
        }

        $instagram->setProxy($ig_profile->proxy);

        try {
            $explorer_response = $instagram->login($ig_username, $ig_password);
            $activity_response = $instagram->people->getRecentActivityInbox();
            $newest_timestamp = 0;
            
            foreach ($activity_response->old_stories as $story) {
                
                if ($story->type == 3) {
                    
                    if ($newest_timestamp == 0) {
                        $newest_timestamp = $story->args->timestamp;
                        //update instagram profile's timestamp here.
                        DB::update("UPDATE user_insta_profile SET recent_activity_timestamp = ? WHERE id = ?;", [$newest_timestamp, $ig_profile->id]);
                    }

                    $new_follower_template = $ig_profile->insta_new_follower_template;
                    $follow_up_template = $ig_profile->follow_up_message;

                    $existing_dm_jobs = DB::select("SELECT job_id
                                FROM `insta_affiliate`.`dm_job`
                                WHERE insta_username = ?
                                AND recipient_insta_id = ?;", [$ig_username, $story->args->profile_id]);

                    $job_exists = 0;
                    
                    foreach ($existing_dm_jobs as $existing_dm_job) {
                        echo("\n[$ig_username]Dm job exists!");
                        $job_exists = 1;
                        break;
                    }
                    
                    if ($job_exists) {
                        break;
                    }
                    
                    if (floatval($ig_profile->recent_activity_timestamp) < floatval($story->args->timestamp)) {

                        #echo("queue as new dm");
                        $user_info_response = $instagram->people->getInfoById($story->args->profile_id);
                        $new_follower = $user_info_response->user;
                        #echo($new_follower->full_name);

                        if ($new_follower->full_name) {
                            $message = str_replace("\${full_name}", $new_follower->full_name, $new_follower_template);
                        } else if (empty($new_follower->full_name)) {
                            $message = str_replace(" \${full_name}", "", $new_follower_template);
                        }

                        preg_match_all('/{([^}]+)}/', $message, $m);

                        $string_replacement = $message;
                        #echo(serialize($m) . "\n\n");
                        for ($j = 0; $j < count($m[1]); $j++) {
                            # Matched text = $result[0][$i];

                            $selected_opt = "";

                            $string_to_replace = $m[1][$j];
                            if (strpos($string_to_replace, '|') !== false) {
                                $string_opts = explode("|", $string_to_replace);
                                $max = count($string_opts);
                                $max--;
                                $index = rand(0, $max);
                                $selected_opt = $string_opts[$index];
                            } else {
                                $selected_opt = $string_to_replace;
                            }

                            $string_replacement = str_replace("\${" . $string_to_replace . "}", $selected_opt, $string_replacement);
                        }

                        echo $string_replacement . "\n\n";

                        //insert job into db here.
//                                $time_to_send = \Carbon\Carbon::now();
//                                $dm_job = new \App\DmJob;
//                                $dm_job->insta_username = $ig_username;
//                                $dm_job->recipient_username = $new_follower->username;
//                                $dm_job->recipient_insta_id = $new_follower->pk;
//                                $dm_job->recipient_fullname = $new_follower->full_name;
//                                $dm_job->follow_up_order = 0;
//                                $dm_job->message = $string_replacement;
//                                $dm_job->time_to_send = $time_to_send;
//                                $dm_job->save();

                        DB::insert("INSERT INTO dm_job (insta_username, recipient_username, recipient_insta_id, recipient_fullname, follow_up_order, message, time_to_send) "
                                . "VALUES (?,?,?,?,?,?,NOW());", [$ig_username, $new_follower->username, $new_follower->pk, $new_follower->full_name, 0, $string_replacement]);

                        $follow_up_message = "";
                        if (!is_null($follow_up_template)) {
                            $follow_up_message = trim($follow_up_template);
                        }

                        if ((!is_null($follow_up_message) && $follow_up_message != "") && $user->user_tier > 1) {

                            if ($new_follower->full_name) {
                                $message2 = str_replace("\${full_name}", $new_follower->full_name, $follow_up_message);
                            } else if (empty($new_follower->full_name)) {
                                $message2 = str_replace(" \${full_name}", "", $follow_up_message);
                            }

                            preg_match_all('/{([^}]+)}/', $message2, $m2);

                            $string_replacement2 = $message2;

                            for ($k = 0; $k < count($m2[1]); $k++) {
                                $selected_opt = "";

                                $string_to_replace2 = $m2[1][$k];
                                if (strpos($string_to_replace2, '|') !== false) {
                                    $string_opts = explode("|", $string_to_replace2);
                                    $max = count($string_opts);
                                    $max--;
                                    $index = rand(0, $max);
                                    $selected_opt = $string_opts[$index];
                                } else {
                                    $selected_opt = $string_to_replace2;
                                }

                                $string_replacement2 = str_replace("\${" . $string_to_replace2 . "}", $selected_opt, $string_replacement2);
                            }

                            echo $string_replacement2 . "\n\n";

                            //insert job into db here.
//                                    $time_to_send = \Carbon\Carbon::now();
//                                    $time_to_send->addDay(1);
//                                    $dm_job = new \App\DmJob;
//                                    $dm_job->insta_username = $ig_username;
//                                    $dm_job->recipient_username = $new_follower->username;
//                                    $dm_job->recipient_insta_id = $new_follower->pk;
//                                    $dm_job->recipient_fullname = $new_follower->full_name;
//                                    $dm_job->follow_up_order = 0;
//                                    $dm_job->message = $string_replacement2;
//                                    $dm_job->time_to_send = $time_to_send;
//                                    $dm_job->save();

                            DB::insert("INSERT INTO dm_job (insta_username, recipient_username, recipient_insta_id, recipient_fullname, follow_up_order, message, time_to_send) "
                                    . "VALUES (?,?,?,?,?,?,NOW());", [$ig_username, $new_follower->username, $new_follower->pk, $new_follower->full_name, 1, $string_replacement2]);
                        }
                    } else {
                        break;
                    }

                    #echo("\n");
                }
            }

            //limit to 1 acct, testing.
//                    break;
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            #echo($checkpoint_ex->getMessage());
        } catch (\Symfony\Component\Debug\Exception\FatalThrowableError $fatalthrowable_ex) {
            #echo($fatalthrowable_ex->getTraceAsString());
        }
    }

}
