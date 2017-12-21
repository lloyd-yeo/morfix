<?php

namespace App\Console\Commands;

use App\BraintreeTransaction;
use Illuminate\Console\Command;
use \Braintree_Transaction;
use \Braintree_Configuration;
use Carbon\Carbon;

class GetBraintreeTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'braintree:listtransactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
	    \Braintree_Configuration::environment('production');
	    \Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
	    \Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
	    \Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

	    $collection = \Braintree_Transaction::search([
		    \Braintree_TransactionSearch::amount()->greaterThanOrEqualTo('37')
	    ]);
		$transaction_ids = $collection->getIds();

		foreach ($transaction_ids as $transaction_id) {
			$transaction = Braintree_Transaction::find($transaction_id);

			$braintree_transaction = BraintreeTransaction::where('id', $transaction_id)->first();

			if ($braintree_transaction == NULL) {
				$braintree_transaction = new BraintreeTransaction;
			}

			$braintree_transaction->id = $transaction->id;
			$braintree_transaction->status = $transaction->status;
			$braintree_transaction->type = $transaction->type;
			$braintree_transaction->amount = $transaction->amount;
			$braintree_transaction->created_at = $transaction->createdAt;
			$braintree_transaction->updated_at = Carbon::now();
			$braintree_transaction->braintree_id = $transaction->customerDetails->id;
			$braintree_transaction->user_email = $transaction->customerDetails->email;
			$braintree_transaction->plan_id = $transaction->planId;
			$braintree_transaction->sub_id = $transaction->subscriptionId;
			$braintree_transaction->bt_cc_token = $transaction->creditCardDetails->token;
			$braintree_transaction->save();

//			dump($transaction);
//			break;
		}
    }
}
