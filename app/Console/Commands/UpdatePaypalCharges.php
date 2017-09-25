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
        if (config('paypal.settings.mode') == 'live') {
            $this->client_id = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }

        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $dt = "I-MK8ENKH9C8XK";
        $client = new \GuzzleHttp\Client();

        $request = $client->get('https://api.sandbox.paypal.com/v1/payments/billing-agreements/' . $dt . '/transaction?start_date=2017-06-15&end_date=2017-09-17 \
-H "Content-Type:application/json" \
-H "Authorization: Bearer"  . $this->apiContext');

        $response = $request->getBody();

        dump($response);
    }

}
