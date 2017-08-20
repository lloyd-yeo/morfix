<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\InstagramProfile;


class EngagementGroupManual extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagementgroup:like {media_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run engagement group on a media_id.';

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
        $ig_profiles = InstagramProfile::where('checkpoint_required', 0)
                ->where('account_disabled', 0)
                ->where('invalid_user', 0)
                ->where('incorrect_pw', 0)
                ->where('invalid_proxy', 0)
                ->get();
        
        $mediaId = $this->argument('media_id');
        
        foreach ($ig_profiles as $ig_profile) {

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
            } catch (\InstagramAPI\Exception\InvalidUserException $invalid_user_ex) {
                $ig_profile->invalid_user = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                continue;
            }
            
            try {
                $response = $instagram->media->like($mediaId);
            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_required_ex) {
                continue;
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                continue;
            }
            
            var_dump ($response);
        }
    }

}
