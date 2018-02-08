<?php

namespace App\Console\Commands;

use App\AddProfileRequest;
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
use App\InstagramHelper;

class ManualLogin extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:login {ig_username} {ig_password} {add_profile_request_id} {proxy?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to Instagram.';

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
        $ig_username = $this->argument("ig_username");
        $ig_password = $this->argument("ig_password");
	    $add_profile_request = AddProfileRequest::where('id', $this->argument("add_profile_request_id"))->first();
	    $instagram = InstagramHelper::initInstagram();

	    $proxy = NULL;
        if ($this->argument("proxy") !== NULL) {
	        $proxy = $this->argument("proxy");
	        $instagram->setProxy($proxy);
        } else {
//	        $proxy = Proxy::inRandomOrder()->first();
//	        $instagram->setProxy($proxy->proxy);
//	        $this->line("Not using Proxy.");
        }

        $this->line($ig_username . " " . $ig_password);

        try {
	        $profile_log = CreateInstagramProfileLog::where('log_id', $add_profile_request->create_profile_log_id)->first();
	        $user = User::where('email', $profile_log->email)->first();
	        $explorer_response = $instagram->login($ig_username, $ig_password);

	        if ($explorer_response !== NULL) {
		        $profile_log->error_msg = $explorer_response->asJson();
		        $profile_log->save();

		        $explorer_response_as_assoc_array = $explorer_response->asJson(1);
		        if ($explorer_response_as_assoc_array["status"] == "fail") {
			        if (array_key_exists("two_factory_required", $explorer_response_as_assoc_array)) {
				        if ($explorer_response_as_assoc_array["two_factory_required"]) {
					        return Response::json([ "success" => FALSE, 'type' => 'endpoint', 'response' => "Account is protected with 2FA, unable to establish connection." ]);
				        }
			        }
		        }
	        }

	        $morfix_ig_profile                 = new InstagramProfile();
	        $morfix_ig_profile->user_id        = $user->user_id;
	        $morfix_ig_profile->email          = $user->email;
	        $morfix_ig_profile->insta_username = $ig_username;
	        $morfix_ig_profile->insta_pw       = $ig_password;

	        $user_response  = $instagram->people->getInfoByName($ig_username);
	        $instagram_user = $user_response->getUser();

	        $profile_log->error_msg = "Profile successfully created.";
	        $profile_log->save();

	        $user->last_used_proxy = NULL;
	        $user->save();

	        $morfix_ig_profile->profile_pic_url = $instagram_user->getProfilePicUrl();
	        $morfix_ig_profile->save();


	        DB::update("UPDATE user_insta_profile SET updated_at = NOW(), follower_count = ?, num_posts = ?, insta_user_id = ? WHERE insta_username = ?;",
		        [ $instagram_user->getFollowerCount(), $instagram_user->getMediaCount(), $instagram_user->getPk(), $ig_username ]);

	        $items = $instagram->timeline->getSelfUserFeed()->getItems();

	        foreach ($items as $item) {
		        try {
			        DB::insert("INSERT IGNORE INTO user_insta_profile_media (insta_username, media_id, image_url) 
				VALUES (?,?,?);", [ $ig_username, $item->getPk(), $item->getImageVersions2()->getCandidates()[0]->getUrl() ]);
		        }
		        catch (\ErrorException $e) {
			        break;
		        }
	        }

	        $add_profile_request->working_on = 5;
	        $add_profile_request->save();

        } catch (\InstagramAPI\Exception\ChallengeRequiredException $challenge_required_ex) {

        	$challenge_url = $challenge_required_ex->getResponse()->asArray()["challenge"]["url"];
	        $add_profile_request->challenge_url = $challenge_url;
	        $add_profile_request->working_on = 2;
	        $add_profile_request->save();

	        dump($challenge_url);
        } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
            dump($emptyresponse_ex);
        } catch (\InstagramAPI\Exception\InstagramException $instagramException) {
	        dump($instagramException);
        }

//        $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy ORDER BY RAND();");
//        foreach ($proxies as $proxy) {
//            $this->line($proxy->proxy);
//            $instagram->setProxy($proxy->proxy);
//            $explorer_response = $instagram->login($ig_username, $ig_password);
//            $this->line(serialize($explorer_response));
//            
//            $ig_profile = InstagramProfile::where('insta_username', $ig_username)->first();
//            if ($ig_profile !== NULL) {
//                $profile_pics = $instagram->getCurrentUser()->user->hd_profile_pic_versions;
//                foreach ($profile_pics as $profile_pic) {
//                    $ig_profile->profile_pic_url = $profile_pic->url;
//                    $ig_profile->save();
//                }
//            }
//            break;
//        }
    }

}
