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

        $apiClientId = 'AebHsSdZdePT3omEiLlf9ZWCUNHU6P5LFIjT9Ba9WHg7VLJiYVXZKhJk3T34mbb-2NtEAWCM2VRUe2Oy';
        $apiClientSecret = 'EByg2Ma7kSbvGlESzJ1Qa1r7KqUxE7loeR60WnJfcvKeY7FHEGONEeTrA0yRkqjktWrinZUCc7_lMUBD';

        $client = new \GuzzleHttp\Client();

        $authResponse = $client->post("https://api.sandbox.paypal.com/v1/oauth2/token", [
            'auth' => [$apiClientId, $apiClientSecret, 'basic'],
            'json' => ['grant_type' => 'client_credentials'],
            'headers' => [
                'Accept-Language' => 'en_US',
                'Accept' => 'application/json'
            ]
        ]);

        echo $authResponse->getBody();
//        $dt = "I-MK8ENKH9C8XK";
//        $client = new \GuzzleHttp\Client();
//
//        $request = $client->get('https://api.sandbox.paypal.com/v1/payments/billing-agreements/' . $dt . '/transaction?start_date=2017-06-15&end_date=2017-09-17');
//
//        $response = $request->getBody();
//
//        dump($response);
    }

}
