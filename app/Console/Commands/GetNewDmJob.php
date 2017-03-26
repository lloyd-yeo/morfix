<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\InstagramException as InstagramException;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use App\InstagramProfile as InstagramProfile;
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

            $instagram_profiles = DB::connection('mysql_old')->select("SELECT insta_username, insta_pw FROM user_insta_profile WHERE user_id = ?;", [$user->user_id]);

            foreach ($instagram_profiles as $ig_profile) {
                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                $ig_username =  $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;
                $config = array();
                $config["type"] = "mysql";
                $config["db_username"] = "root";
                $config["db_password"] = "inst@ffiliates123";
                $config["db_host"] = "52.221.60.235:3306";
                $config["db_name"] = "morfix";
                $config["db_tablename"] = "instagram_sessions";
                $settings_adapter = new SettingsAdapter($config, $ig_username);
                $instagram = new Instagram(false, false, [
                    'type' => 'mysql',
                    'db_username' => 'root',
                    'db_password' => 'inst@ffiliates123',
                    'db_host' => '52.221.60.235:3306',
                    'db_name' => 'morfix',
                    'db_tablename' => 'instagram_sessions'
                ]);

                $proxy = Proxy::where('assigned', '=', 0)->first();
                $instagram->setProxy($proxy->proxy);
                $proxy->assigned = 1;
                $proxy->save();

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $user_response = $instagram->getUserInfoByName($ig_username);
                    $instagram_user = $user_response->user;

                    $new_profile = new InstagramProfile;
                    $new_profile->user_id = $user->user_id;
                    $new_profile->email = $user->email;
                    $new_profile->insta_user_id = $instagram_user->pk;
                    $new_profile->insta_username = $ig_username;
                    $new_profile->insta_pw = $ig_password;
                    $new_profile->profile_pic_url = $instagram_user->profile_pic_url;
                    $new_profile->profile_full_name = $instagram_user->full_name;
                    $new_profile->follower_count = $instagram_user->follower_count;
                    $new_profile->num_posts = $instagram_user->media_count;
                    $new_profile->proxy = $proxy->proxy;
                    $new_profile->save();
                    $this->line(serialize($user_response));
                    
                } catch (InstagramException $ig_ex) {
                    $this->line($ig_ex->getMessage());
//                    $log = CreateInstagramProfileLog::find($last_inserted_log_id);
//                    $log->error_msg = $ig_ex->getTraceAsString();
//                    $log->save();
//                    $message = $ig_ex->getMessage();
//                    $array = explode(':', $message);
//                    return Response::json(array("success" => false, 'response' => trim($array[1]), "log" => $last_inserted_log_id));
                }
            }
        }
    }

}
