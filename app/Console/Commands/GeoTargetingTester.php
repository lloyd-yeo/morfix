<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use Illuminate\Console\Command;
use App\InstagramProfile;
use App\User;
use InstagramAPI\Signatures;

class GeoTargetingTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geotargeting:like {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test like with geo targeting';

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
        $email = $this->argument("email");

        $ig_profile = InstagramProfile::where('email', $email)->first();

        $instagram = InstagramHelper::initInstagram();

        $instagram->login($ig_profile->insta_username, $ig_profile->insta_pw);

        $locations = $instagram->location->search(0, 0, 'Singapore');
	    dump($locations);
	    $rankToken = \InstagramAPI\Signatures::generateUUID();
        $feed = $instagram->location->getFeed('105565836144069', $rankToken, null);
        dump($feed);
//        $locations = $instagram->location->getFeed()
//        dump($locations);

    }

}
