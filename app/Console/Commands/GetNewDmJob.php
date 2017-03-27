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

class GetNewDmJob extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:get {offset : The position to start retrieving from.} {limit : The number of results to limit to.} ';

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
        $offset = $this->argument('offset');
        $limit = $this->argument('limit');

        $users = DB::connection('mysql_old')->select("SELECT * FROM user WHERE tier > 1 OR admin = 1 OR vip = 1 ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);

        foreach ($users as $user) {
            $this->line($user->user_id);

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT id, insta_username, insta_pw, proxy, recent_activity_timestamp, insta_new_follower_template, follow_up_message FROM user_insta_profile WHERE user_id = ?;", [$user->user_id]);

            foreach ($instagram_profiles as $ig_profile) {
                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

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

                if (is_null($ig_profile->proxy)) {
                    $proxy = Proxy::where('assigned', '=', 0)->first();
                    $instagram->setProxy($proxy->proxy);
                    $proxy->assigned = 1;
                    $proxy->save();
                } else {
                    $instagram->setProxy($ig_profile->proxy);
                }

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $activity_response = $instagram->getRecentActivity();
                    $newest_timestamp = 0;
                    foreach ($activity_response->old_stories as $story) {
                        if ($story->type == 3) {
                            if ($newest_timestamp == 0) {
                                $newest_timestamp = $story->args->timestamp;
                                //update instagram profile's timestamp here.
                            }
                            
                            $new_follower_template = $ig_profile->insta_new_follower_template;
                            $follow_up_template = $ig_profile->follow_up_message;

                            $this->line($story->args->text);
                            $this->line($story->type);
                            $this->line($story->args->profile_id);
                            $this->line($story->args->timestamp);
                            
                            if (DmJob::where('insta_username', '=', $ig_username)->where('recipient_insta_id', '=', $story->args->profile_id)->count() > 0) {
                                // dm job exists
                                $this->line("job exists!");
                                break;
                            }
                            
                            if (floatval($ig_profile->recent_activity_timestamp) < floatval($story->args->timestamp)) {
                                
                                $this->line("queue as new dm");

                                $user_info_response = $instagram->getUserInfoById($story->args->profile_id);
                                $this->line(serialize($user_info_response));
                                
                                $new_follower = $user_info_response->user;

                                if ($new_follower->full_name) {
                                    $message = str_replace("\${full_name}", $new_follower->full_name, $new_follower_template);
                                } else if (empty($new_follower->full_name)) {
                                    $message = str_replace(" \${full_name}", "", $new_follower_template);
                                }

                                preg_match_all('/{([^}]+)}/', $message, $m);

                                $string_replacement = $message;
                                $this->line(serialize($m) . "\n\n");
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
                                $time_to_send = \Carbon\Carbon::now();
                                $dm_job = new \App\DmJob;
                                $dm_job->insta_username = $ig_username;
                                $dm_job->recipient_username = $new_follower->username;
                                $dm_job->recipient_insta_id = $new_follower->pk;
                                $dm_job->recipient_fullname = $new_follower->full_name;
                                $dm_job->follow_up_order = 0;
                                $dm_job->message = $string_replacement;
                                $dm_job->time_to_send = $time_to_send;
                                $dm_job->save();
                                
                                $follow_up_message = trim($follow_up_template);

                                if (!is_null($follow_up_message) && $follow_up_message != "") {
                                    
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
                                    $time_to_send = \Carbon\Carbon::now();
                                    $time_to_send->addDay(1);
                                    $dm_job = new \App\DmJob;
                                    $dm_job->insta_username = $ig_username;
                                    $dm_job->recipient_username = $new_follower->username;
                                    $dm_job->recipient_insta_id = $new_follower->pk;
                                    $dm_job->recipient_fullname = $new_follower->full_name;
                                    $dm_job->follow_up_order = 0;
                                    $dm_job->message = $string_replacement;
                                    $dm_job->time_to_send = $time_to_send;
                                    $dm_job->save();
                                }
                            } else {
                                break;
                            }

                            $this->line("\n");
                        }
                    }
                    
                    //limit to 1 acct, testing.
                    break;
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    $this->line($checkpoint_ex->getMessage());
                }
            }
        }
    }
}
