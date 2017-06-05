<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionComment extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:comment {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comment on target user\'s photos.';

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

        $users = array();

        if (NULL !== $this->argument("email")) {
            $user = User::where("email", $this->argument("email"))->first();

            $instagram_profiles = InstagramProfile::where('auto_interaction', true)
                    ->where('auto_comment', true)
                    ->where('email', $user->email)
                    ->get();

            executeCommenting($instagram_profiles);
        } else {
            foreach (User::cursor() as $user) {

                

                if ($user->tier < 2) {
                    continue;
                }
                
                $instagram_profiles = InstagramProfile::where('auto_interaction', true)
                        ->where('auto_comment', true)
                        ->where('email', $user->email)
                        ->whereRaw('NOW() >= next_comment_time')
                        ->get();
                
                if (count($instagram_profiles) > 0) {
                    $this->line($user->user_id);
                }
                
                executeCommenting($instagram_profiles);
            }
        }
    }

}

function executeCommenting($instagram_profiles) {

    foreach ($instagram_profiles as $ig_profile) {

        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw . "\n");

        $ig_username = $ig_profile->insta_username;
        $ig_password = $ig_profile->insta_pw;

        $config = array();
        $config['pdo'] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        $config["storage"] = "mysql";

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

            $comment = InstagramProfileComment::where('insta_username', $ig_username)
                    ->inRandomOrder()
                    ->first();

            if ($comment === NULL) {
                continue;
            }
            
            echo($comment->comment . "\n");
            
            $unengaged_followings = InstagramProfileFollowLog::where('insta_username', $ig_username)
                                                    ->whereRaw("follower_username NOT IN "
                                                            . "(SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = \"$ig_username\")")
                                                    ->orderBy('date_inserted', 'desc')
                                                    ->take(5)
                                                    ->get();
            if (count($unengaged_followings) > 0) {
                continue;
                
                foreach ($unengaged_followings as $unengaged_following) {
                    echo("[$ig_username] \t" . $unengaged_following->follower_username . "\n");
                }
            } else {
                
                $unengaged_likings = InstagramProfileLikeLog::where('insta_username', $ig_username)
                                                            ->whereRaw("target_username NOT IN "
                                                            . "(SELECT target_username FROM user_insta_profile_comment_log WHERE insta_username = \"$ig_username\")")
                                                            ->orderBy('date_liked', 'desc')
                                                            ->take(5)
                                                            ->get();
                
                foreach ($unengaged_likings as $unengaged_liking) {
                    echo("[$ig_username] \t" . $unengaged_liking->target_username . "\n");
                }
                
            }
            
            #$instagram->setUser($ig_username, $ig_password);
            #$login_resp = $instagram->login();
            
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpt_ex) {
            echo("checkpt1 " . $checkpt_ex->getMessage() . "\n");
            $ig_profile->checkpoint_required = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            echo("incorrectpw1 " . $incorrectpw_ex->getMessage() . "\n");
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            echo("endpt1 " . $endpoint_ex->getMessage() . "\n");
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            echo("network1 " . $network_ex->getMessage() . "\n");
        } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
            echo("acctdisabled1 " . $acctdisabled_ex->getMessage() . "\n");
            $ig_profile->account_disabled = 1;
            $ig_profile->save();
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            echo("request1 " . $request_ex->getMessage() . "\n");
            $ig_profile->error_msg = $request_ex->getMessage();
            $ig_profile->save();
        }
    }
}
