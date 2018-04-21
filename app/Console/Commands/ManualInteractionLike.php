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
    protected $signature = 'ig:like';

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
		$instagram = InstagramHelper::initInstagram(true);
		$ig_profile = InstagramProfile::where('insta_username', 'theluxurymaker')->first();
	    $guzzle_options                                 = [];
	    $guzzle_options['curl']                         = [];
	    $guzzle_options['curl'][CURLOPT_PROXY]          = 'http://' . $ig_profile->proxy;
	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD]   = 'morfix:dXehM3e7bU';
	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;
    }
}
