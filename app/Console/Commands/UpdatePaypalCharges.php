<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;

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

        $this->client_id = config('paypal.live_client_id');
        $this->secret = config('paypal.live_secret');

        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));

        $agreementId = "I-MK8ENKH9C8XK";

        $params = array('start_date' => date('Y-m-d', strtotime('-15 years')), 'end_date' => date('Y-m-d', strtotime('+30 days')));
        
        try {
            $result = Agreement::searchTransactions($agreementId, $params, $this->apiContext);
            dump($result);
        } catch (\Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            $this->error($ex->getMessage());
        }

//        $uri = 'https://api.sandbox.paypal.com/v1/oauth2/token';
//
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('POST', $uri, [
//            'headers' =>
//            [
//                'Accept' => 'application/json',
//                'Accept-Language' => 'en_US',
//                'Content-Type' => 'application/x-www-form-urlencoded',
//            ],
//            'body' => 'grant_type=client_credentials',
//            'auth' => [$this->client_id, $this->secret, 'basic']
//                ]
//        );
//
//        $data = json_decode($response->getBody(), true);
//
//        $access_token = $data['access_token'];
//        echo 'access token retrieved';
//
//        $dt = "I-MK8ENKH9C8XK";
//
//        $uri2 = 'https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-MK8ENKH9C8XK/transaction?start_date=2017-06-15&end_date=2017-06-17';
//
//        $response2 = $client->request('GET', $uri2, [
//            'headers' =>
//            [
//                'Content-Type' => 'application/json',
//                'Authorization' => ' Bearer ' . $access_token
//            ]
//        ]);
//
//        $data2 = json_decode($response2->getBody(), true);
//
////        ,
////            'body' => 'grant_type=client_credentials',
////            'auth' => [$this->client_id, $this->secret, 'basic']
//        dump($data2);
    }

}
