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

class RefreshInstagramProfile extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:refresh {offset : The position to start retrieving from.} {limit : The number of results to limit to.} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the stats for a user\'s instagram profile.';

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

        if (NULL !== $this->argument("email")) {
            $users = User::where('email', $this->argument("email"))->get();
        } else {
            $users = User::where(DB::raw('email IN (SELECT email FROM user_insta_profile)'))
                    ->orderBy('user_id', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
        }

        foreach ($users as $user) {
            $this->line($user->email);
            $instagram_profiles = InstagramProfile::where('checkpoint_required', 0)
                    ->where('incorrect_pw', 0)
                    ->where('invalid_user', 0)
                    ->where('user_id', $user->user_id)
                    ->get();
            
            $config = array();
            $config["storage"] = "mysql";
            $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
            $config["dbtablename"] = "instagram_sessions";
            
            $debug = false;
            $truncatedDebug = false;
            
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
            foreach ($instagram_profiles as $ig_profile) {
                $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
                
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

                if ($ig_profile->proxy === NULL) {
                    $proxy = Proxy::inRandomOrder()->first();
                    $ig_profile->proxy = $proxy->proxy;
                    $ig_profile->error_msg = NULL;
                    $ig_profile->save();
                    $proxy->assigned = $proxy->assigned + 1;
                    $proxy->save();
                }

                $instagram->setProxy($ig_profile->proxy);

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $login_response = $instagram->login();
                    var_dump($login_response);
                    $user_response = $instagram->getUserInfoByName($ig_username);
                    $instagram_user = $user_response->user;
                    
                    DB::update("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ?, profile_pic_url = ? WHERE insta_username = ?;", 
                                    [$instagram_user->follower_count, $instagram_user->media_count, $instagram_user->pk, $instagram_user->profile_pic_url, $ig_username]);
                    
                    $items = $instagram->timeline->getSelfUserFeed()->items;
//                    $this->info(serialize($items));
                    foreach ($items as $item) {
                        try {
                            DB::connection('mysql_old')->
                                    insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) VALUES (?,?,?);", [$ig_username, $item->id, $item->image_versions2->candidates[0]->url]);
                        } catch (\ErrorException $e) {
                            $this->error("ERROR: " . $e->getMessage());
                            break;
                        }
                    }
//                    $new_profile = new InstagramProfile;
//                    $new_profile->user_id = Auth::user()->id;
//                    $new_profile->email = Auth::user()->email;
//                    $new_profile->insta_user_id = $instagram_user->pk;
//                    $new_profile->insta_username = $ig_username;
//                    $new_profile->insta_pw = $ig_password;
//                    $new_profile->profile_pic_url = $instagram_user->profile_pic_url;
//                    $new_profile->profile_full_name = $instagram_user->full_name;
//                    $new_profile->follower_count = $instagram_user->follower_count;
//                    $new_profile->num_posts = $instagram_user->media_count;
//                    $new_profile->proxy = $proxy;
//                    $new_profile->save();
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    $this->error($checkpoint_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    $this->error($network_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error($endpoint_ex->getMessage());
                    if (stripos(trim($endpoint_ex->getMessage()), "The username you entered doesn't appear to belong to an account. Please check your username and try again.") !== false) {
                        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
                        $instagram->setUser("entrepreneur_xyz", "instaffiliates123");
                        $instagram->login();
                        $resp = serialize($instagram->getUserInfoById($ig_profile->insta_user_id));
                        DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$resp, $ig_profile->id]);
                    } else {
                        DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                    }
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    $this->error($incorrectpw_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
                    $this->error($accountdisabled_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set account_disabled = 1, error_msg = ? where id = ?;', [$accountdisabled_ex->getMessage(), $ig_profile->id]);
                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                    $this->error($request_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$request_ex->getMessage(), $ig_profile->id]);
                }
            }
        }
    }

}
