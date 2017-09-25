<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use PayPal\Api\AgreementTransactions;
use App\User;
use PayPal\Common\PayPalModel;
use GuzzleHttp\Client;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;


class UpdatePaypalCharges extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdatePaypal:Charges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Paypal Charges';

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
        
         $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
            
             $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));

    
        $uri = 'https://api.sandbox.paypal.com/v1/oauth2/token';

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $uri, [
            'headers' =>
            [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => 'grant_type=client_credentials',
            'auth' => [$this->client_id, $this->secret, 'basic']
                ]
        );

        $data = json_decode($response->getBody(), true);

        $access_token = $data['access_token'];
        echo 'access token retrieved';
        
        $dt = "I-MK8ENKH9C8XK";
        
        $uri2= 'https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-MK8ENKH9C8XK/transaction?start_date=2017-06-15&end_date=2017-06-17';
                
  
        $response2 = $client->request('GET', $uri2, [
            'headers' =>
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer' . $access_token
            ],
            'body' => 'grant_type=client_credentials',
            'auth' => [$this->client_id, $this->secret, 'basic']            
        ]);
        $data2 = json_decode($response2->getBody(), true);

        dump($data2);
    }

}
