<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use App\PaypalCharges;
use App\PaypalAgreement;
use Carbon\Carbon;
use App\GetReferralForUser;

class UpdatePaypalChargesDaily extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:paypalchargesdaily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update paypal charges for new updates';

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
        $time_start = microtime(true);

        $this->client_id = config('paypal.live_client_id');
        $this->secret = config('paypal.live_secret');

        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));

        $users = PaypalAgreement::distinct()
                ->orderBy('id', 'desc')
                ->get();

        foreach ($users as $user) {
            $agreementId = $user->agreement_id;
            $params = array('start_date' => date('Y-m-d', strtotime('-15 years')), 'end_date' => date('Y-m-d', strtotime('+30 days')));
            try {
                $results = Agreement::searchTransactions($agreementId, $params, $this->apiContext)->agreement_transaction_list;
                // dump($results);
                foreach ($results as $result) {

                    $checktransactionid = PaypalCharges::where('transaction_id', $result->transaction_id)
                            ->first();

                    if ($result->status == "Created") {
                        if ($checktransactionid === NULL) {
                            $this->CreateNewPaypalCharge($user, $result, $agreementId);
                        }
                    }
                    if ($result->status == "Cancelled") {
                        $checkexist = PaypalCharges::where('transaction_id', $result->transaction_id)
                                ->where('status', $result->status)
                                ->first();
                        if ($checkexist === NULL) {
                            $this->CreateNewPaypalCharge($user, $result, $agreementId);
                        }
                    }
                    if ($result->status == "Refunded") {
                        $checkexist = PaypalCharges::where('transaction_id', $result->transaction_id)
                                ->where('status', $result->status)
                                ->first();
                        if ($checkexist === NULL) {
                            $this->CreateNewPaypalCharge($user, $result, $agreementId);
                        } else if ($checkexist !== NULL) {
                            $updatecharge = PaypalCharges::where('transaction_id', $result->transaction_id)
                                    ->where('status', "Completed")
                                    ->update(['status' => "Refunded"]);
                        }
                    }
                    if ($result->status == "Completed") {
                        $checkexist = PaypalCharges::where('transaction_id', $result->transaction_id)
                                ->where('status', $result->status)
                                ->first();
                        if ($checkexist === NULL){
                             $this->CreateNewPaypalCharge($user, $result, $agreementId);
                        }
                    }
                }
            } catch (\Exception $ex) {
                // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
                dump($ex);
            }
        }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
    }

    public function CreateNewPaypalCharge($user, $result, $agreementId) {
        $charge = new PaypalCharges;
        $charge->email = $user->email;
        $charge->agreement_id = $agreementId;
        $charge->transaction_id = $result->transaction_id;
        $charge->status = $result->status;
        $charge->transaction_type = $result->transaction_type;
        $charge->payer_email = $result->payer_email;
        $charge->payer_name = $result->payer_name;
        $timestamp = $result->time_stamp;
        $timestamp = str_replace("T", " ", $timestamp);
        $timestamp = str_replace("Z", "", $timestamp);
        $charge->time_stamp = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->setTimezone('Asia/Singapore')->toDateTimeString();
        if ($result->amount !== NULL) {
            $charge->amount = $result->amount->value;
            switch ($result->amount->value) {
                case "37.00":
                    $charge->subscription_id = "0137";
                    break;
                case "97.00":
                    $charge->subscription_id = "0297";
                    break;
                case "0.00":
                    $charge->subscription_id = "never pay money";
                    break;
            }
        }
        $referrers = GetReferralForUser::fromView()
                ->where('referred', $user->email)
                ->first();
        if ($referrers !== NULL) {
            $charge->referrer_email = $referrers->referrer;
        }
        echo 'new transaction saved: [' . $result->status . '] for [' . $user->email . "]\n";
        $charge->save();
    }

}
