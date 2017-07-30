<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileTargetHashtag;
use App\EngagementJob;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\Niche;

class ManuallyFollowBack extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:follow {insta_username} {profile_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually follow back users for a certain ig profile.';

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
        $profile_to_follow_id = $this->argument('profile_id');
        $ig_profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();

        echo("\n" . $ig_profile->insta_username . "\t" . $ig_profile->insta_pw);

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

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

        $instagram->setUser($ig_username, $ig_password);
        
        try {
            $explorer_response = $instagram->login();
            var_dump($explorer_response);
        } catch (\InstagramAPI\Exception\SentryBlockException $sentry_block_ex) {
            $proxy = Proxy::inRandomOrder()->first();
            $ig_profile->proxy = $proxy->proxy;
            $ig_profile->save();
            $proxy->assigned = $proxy->assigned + 1;
            $proxy->save();
            exit();
        } catch (\InstagramAPI\Exception\ForcedPasswordResetException $forced_password_reset_ex) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
            exit();
        }
        
        $response = $instagram->people->follow(13202235);
        var_dump($response);
        #echo $ig_profile;
    }

}
