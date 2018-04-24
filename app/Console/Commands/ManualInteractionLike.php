<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use Illuminate\Support\Facades\Redis;
use InstagramAPI;
use App\InstagramHelper;

class ManualInteractionLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:like {ig_username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manual command for testing out interaction like';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ig_username = $this->argument('ig_username');
		$instagram = InstagramHelper::initInstagram(true);
		$ig_profile = InstagramProfile::where('insta_username', $ig_username)->first();
//	    $guzzle_options                                 = [];
//	    $guzzle_options['curl']                         = [];
//	    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
//	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'morfix:dXehM3e7bU';
//	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
        if ($ig_profile->proxy == NULL) {
            $this->info("Using RESIDENTIAL proxy.");
            $guzzle_options                                 = [];
            $guzzle_options['curl']                         = [];
            $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
            $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
            $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
        } else if (strpos($ig_profile->proxy, 'http') === 0) {
            $this->info("Using RESIDENTIAL proxy.");
            $guzzle_options                                 = [];
            $guzzle_options['curl']                         = [];
            $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://pr.oxylabs.io:8000';
            $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
            $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
            $ig_profile->proxy                              = NULL;
            $ig_profile->save();
        } else {
            $this->info("Using DATACENTER proxy.");
            $guzzle_options                                 = [];
            $guzzle_options['curl']                         = [];
            $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
            $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'morfix:dXehM3e7bU';
            $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
        }

        $instagram->setGuzzleOptions($guzzle_options);

        try {

            $login_resp = $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw, $guzzle_options);
            if ($login_resp != NULL) {
                dump($login_resp);
                $ig_profile->proxy = InstagramHelper::getDatacenterProxyList()[rand(1, 99)];
	            $ig_profile->save();
            } else {
                if ($instagram->isMaybeLoggedIn){
                    if($ig_profile->proxy == NULL){
                        $ig_profile->proxy = InstagramHelper::getDatacenterProxyList()[rand(1, 99)];
                        $ig_profile->save();
                    }
                }
//                $user_model_public = $instagram->people->getSelfInfo()->getUser();
//                $ig_profile->profile_full_name = $user_model_public->getFullName();
//                $ig_profile->follower_count = $user_model_public->getFollowerCount();
//                $ig_profile->num_posts = $user_model_public->getMediaCount();
//                $ig_profile->save();
//                dump($user_model_public);
//                dump($instagram->account->getCurrentUser()->getUser());
            }

        } catch (IncorrectPasswordException $incorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
            $ig_profile->save();
        } catch (InstagramException $instagramException) {
            dump($instagramException);
        } catch (NetworkException $networkException) {
            dump($networkException);
        }

//        $userId = $instagram->people->getUserIdForName('adrianentrepreneur');
//        dump($userId);
        $userId = $ig_profile->insta_user_id;
        $rank_token = \InstagramAPI\Signatures::generateUUID(TRUE);
//        $follower_response = $instagram->people->getFollowers($userId, $rank_token);
        $follower_response = $instagram->getUserFeed($userId);
        dump($follower_response);
        echo "This is follower response \n";
//        echo json_encode($follower_response, JSON_PRETTY_PRINT);

//        $pk = "test:profile:12345678";
//        $response_array = (array("name" => "abc", "full_name" => "long_name", "is_verified" => "false", "new" => "haha"));
//        echo "This is follower response \n";
//        $response_array = json_encode($response_array, JSON_PRETTY_PRINT);
//        echo ($response_array);
//        $counter= 0;
//        foreach ($follower_response->getUsers() as $user){
//            Redis::hmset(
//                "test:profile:" . $user->getPk(), $user->asArray()
//            );
//        }


    }
}
