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

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT insta_username, insta_pw, proxy, recent_activity_timestamp, insta_new_follower_template, follow_up_message FROM user_insta_profile WHERE user_id = ?;", [$user->user_id]);

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
//                $settings_adapter = new \InstagramAPI\SettingsAdapter($config, $ig_username);

                $debug = 0;
                $truncatedDebug = 0;
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
                    foreach ($activity_response->old_stories as $story) {
//                        $this->line(serialize($story) . "<br/>");
                        if ($story->type == 3) {
                            $this->line($story->args->text);
                            $this->line($story->type);
                            $this->line($story->args->profile_id);
                            $this->line($story->args->timestamp);
                            if (floatval($ig_profile->recent_activity_timestamp) < floatval($story->args->timestamp)) {
                                $this->line("queue as new dm");
                            }
                            $this->line("\n");
                        }
                    }
                    break;
//                    $timeline_feed = $instagram->getTimelineFeed();
//                    foreach ($timeline_feed->feed_items as $item) {
//                        $this->line(serialize($item));
//                    }
                    
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    $this->line($checkpoint_ex->getMessage());
                }
            }
        }
    }

}
