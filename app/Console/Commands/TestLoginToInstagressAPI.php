<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\RequestOptions;
use InstagramAPI\Exception\RequestException;

class TestLoginToInstagressAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagress:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login with Instagress API';

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
        $username = 'theluxurymaker';
        $password = 'p@ssw0rd123123';

	    $client = new \GuzzleHttp\Client();
	    try {
		    $response = $client->post('https://gress.io/api/accounts/connect', [
			    RequestOptions::JSON => [
				    'token' => '1d73c7c1b10f05f2048f54083e9381ac18f36261a98416a00b7eed6b00d56eb4',
				    'username' => $username,
				    'password' => $password,
			    ]
		    ]);

		    $response_json = json_decode($response->getBody(), true);

		    dump($response_json);

	    } catch (RequestException $request_ex) {

			dump($request_ex);
	    }
    }
}
