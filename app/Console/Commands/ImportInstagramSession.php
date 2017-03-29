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

class ImportInstagramSession extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:import {offset : The position to start retrieving from.} {limit : The number of results to limit to.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import instagram profile sessions from old mysql db.';

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

        $users = DB::connection('mysql_old')->select("SELECT * FROM user ORDER BY user_id ASC LIMIT ?,?;", [$offset, $limit]);

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
                
                if (!is_null($ig_profile->proxy)) {
                    $instagram->setProxy($ig_profile->proxy);
                } else {
                    $proxy = Proxy::where('assigned', '=', 0)->first();
                    $instagram->setProxy($proxy->proxy);
                    $proxy->assigned = 1;
                    $proxy->save();
                }

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $this->line(serialize($instagram->getCurrentUser()));
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
                    $this->error($checkpt_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    $this->error($incorrectpw_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error($endpoint_ex->getMessage());
                    $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                    
                }
            }
        }
    }

}
