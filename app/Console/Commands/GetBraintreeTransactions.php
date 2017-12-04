<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Braintree_Transaction;
use \Braintree_Configuration;

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
	    Braintree_Configuration::environment('production');
	    Braintree_Configuration::merchantId('4x5qk4ggmgf9t5vw');
	    Braintree_Configuration::publicKey('vtq3w9x62s57p82y');
	    Braintree_Configuration::privateKey('c578012b2eb171582133ed0372f3a2ae');

	    $collection = Braintree_Transaction::search([
		    Braintree_TransactionSearch::amount()->greaterThanOrEqualTo('3700')
	    ]);

	    dump($collection);
    }
}
