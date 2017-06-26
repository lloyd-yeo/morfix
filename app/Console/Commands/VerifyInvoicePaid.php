<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class VerifyInvoicePaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that invoice has been paid.';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");

        $path = storage_path('app/invoice-for-may.csv');
        $file = fopen($path, "r");
        $all_data = array();
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            $string = "";
            $invoice_id = $data[0];
            $invoice = \Stripe\Invoice::retrieve($invoice_id);
            $charge = \Stripe\Charge::retrieve($invoice->charge);
            
            if ($invoice->paid) {
                $string = "PAID";
            } else {
                $string = "UNPAID";
            }
            
            if ($charge->refunded) {
                $string = $string . ",REFUNDED\n";
            } else {
                $string = $string . ",NOT REFUNDED\n";
            }
            echo $string;
        }
    }
}
