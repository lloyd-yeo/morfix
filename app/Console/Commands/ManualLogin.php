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
use App\InstagramHelper;

class ManualLogin extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:login {ig_username} {ig_password} {proxy?}';

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
            #$explorer_response = $instagram->login($ig_username, $ig_password);
//            dd($explorer_response);
            dump($instagram->login($ig_username, $ig_password));
            dump($instagram->timeline->getSelfUserFeed());
        } catch (\InstagramAPI\Exception\ChallengeRequiredException $challenge_required_ex) {
	        $challenge_url = $challenge_required_ex->getResponse()->asArray()["challenge"]["url"];
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
