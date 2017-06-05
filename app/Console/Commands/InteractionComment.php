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
                        ->where(DB::raw('NOW() >= next_comment_time'))
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
