<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InstagramAPI\Instagram;
use App\InstagramHelper;

class InstagramLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:lgin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually login to Instagram';

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

        $guzzle_options = array();
	    $guzzle_options['curl'] = array();
	    $guzzle_options['curl'][CURLOPT_PROXY] = 'http://pr.oxylabs.io:8000';
	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD] = 'customer-rmofix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;

	    $instagram->login('adrianentrepreneur',
		    'porkpork', $guzzle_options
	    );

    }
}
