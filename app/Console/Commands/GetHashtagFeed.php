<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use App\InstagramProfile;
use Illuminate\Console\Command;
use InstagramAPI\Signatures;

class GetHashtagFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:hashtagfeed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Hashtag feed';

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
        $ig_profile = InstagramProfile::where('insta_username', 'theluxurymaker')->first();
		if ($ig_profile != NULL) {
			$instagram = InstagramHelper::initInstagram();
			$instagram = InstagramHelper::setProxy($instagram, $ig_profile, 1);
			$login = InstagramHelper::login($instagram, $ig_profile);
			if ($login) {
				$rankToken = Signatures::generateUUID(TRUE);
//				$instagram->push
				dump($instagram->discover->search('#entreprenuer'));
//				dump($instagram->hashtag->getFeed('entreprenuer', $rankToken, NULL));
			} else {

			}
		}
    }
}
